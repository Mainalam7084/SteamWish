document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // 2. Heart Button Animation
    document.addEventListener('click', function (e) {
        const heartBtn = e.target.closest('[data-heart]');
        if (heartBtn) {
            heartBtn.classList.toggle('text-[#5DA9D6]');
            heartBtn.classList.toggle('text-[#0F3A52]');
            heartBtn.classList.add('heart-active');
            setTimeout(() => heartBtn.classList.remove('heart-active'), 300);
        }
    });

    // 3. Wishlist Toggle Button Logic
    document.addEventListener('click', function (e) {
        const wishlistBtn = e.target.closest('.wishlist-btn');
        if (wishlistBtn) {
            e.preventDefault();
            if (window.AppConfig.isGuest) {
                window.location.href = window.AppConfig.routes.login;
                return;
            }
            const appid = wishlistBtn.dataset.appid;
            if (!appid) return;
            fetch(window.AppConfig.routes.wishlistToggle, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.AppConfig.csrfToken
                },
                body: JSON.stringify({ appid: appid })
            })
                .then(res => res.json())
                .then(data => {
                    const btns = document.querySelectorAll(`.wishlist-btn[data-appid="${appid}"]`);
                    btns.forEach(btn => {
                        const textSpan = btn.querySelector('span');
                        if (data.status === 'added') {
                            btn.classList.add('bg-[#FACC15]', 'text-black');
                            btn.classList.remove('bg-white', 'text-[#0F3A52]', 'hover:bg-[#5DA9D6]', 'hover:text-white');
                            if (textSpan) textSpan.textContent = 'Saved';
                        } else {
                            btn.classList.remove('bg-[#FACC15]', 'text-black');
                            btn.classList.add('bg-white', 'text-[#0F3A52]');
                            if (textSpan) textSpan.textContent = 'Add to Wishlist';
                            btn.dispatchEvent(new CustomEvent('wishlist:removed', { bubbles: true }));
                        }
                    });
                });
        }
    });

    // 4. Navbar Wishlist Preview Dropdown
    const navWishlistContainer = document.getElementById('nav-wishlist-container');
    const navWishlistItems = document.getElementById('nav-wishlist-items');
    if (navWishlistContainer && navWishlistItems) {
        let previewLoaded = false;
        navWishlistContainer.addEventListener('mouseenter', () => {
            if (previewLoaded) return;
            previewLoaded = true;
            fetch(window.AppConfig.routes.wishlistPreview)
                .then(res => res.ok ? res.json() : [])
                .then(games => {
                    if (games.length === 0) {
                        navWishlistItems.innerHTML = `
                            <div class="p-4 text-center">
                                <i data-lucide="ghost" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                <p class="text-xs font-bold text-gray-400">Aún no has guardado ningún juego.</p>
                            </div>
                        `;
                    } else {
                        navWishlistItems.innerHTML = games.map(g => `
                            <a href="/game?appid=${g.appid}" class="flex items-center gap-3 p-3 border-b-2 border-black hover:bg-[#FACC15] group transition-colors">
                                <img src="${g.image}" alt="${g.name}" class="w-16 h-8 object-cover border-2 border-black shrink-0">
                                <h4 class="font-black text-[10px] uppercase text-[#0F3A52] line-clamp-2">${g.name}</h4>
                            </a>
                        `).join('');
                    }
                    if (window.lucide) lucide.createIcons();
                })
                .catch(() => {
                    navWishlistItems.innerHTML = `
                        <div class="p-4 text-center">
                            <p class="text-xs font-bold text-red-500">Error al cargar.</p>
                        </div>
                    `;
                });
        });
    }

    // 5. Navbar Notifications Preview Dropdown
    const navNotifContainer = document.getElementById('nav-notifications-container');
    const navNotifItems = document.getElementById('nav-notifications-items');
    const navNotifLabel = document.getElementById('nav-notif-unread-label');
    const navNotifBadge = document.getElementById('nav-notif-badge');
    if (navNotifContainer && navNotifItems && !window.AppConfig.isGuest) {
        let notifLoaded = false;
        navNotifContainer.addEventListener('mouseenter', () => {
            if (notifLoaded) return;
            notifLoaded = true;
            fetch(window.AppConfig.routes.notificationsPreview)
                .then(res => res.ok ? res.json() : { notifications: [], unread: 0 })
                .then(({ notifications, unread }) => {
                    if (navNotifBadge) {
                        if (unread > 0) {
                            navNotifBadge.textContent = unread > 9 ? '9+' : unread;
                            navNotifBadge.style.display = 'flex';
                        } else {
                            navNotifBadge.style.display = 'none';
                        }
                    }
                    if (navNotifLabel) {
                        navNotifLabel.textContent = unread > 0 ? `${unread} sin leer` : 'Todo leído';
                    }
                    if (notifications.length === 0) {
                        navNotifItems.innerHTML = `
                            <div class="p-4 text-center">
                                <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                <p class="text-xs font-bold text-gray-400">Sin notificaciones aún.</p>
                            </div>
                        `;
                    } else {
                        navNotifItems.innerHTML = notifications.map(n => `
                            <a href="${window.AppConfig.routes.notificationsIndex}"
                                class="flex items-start gap-3 p-3 border-b-2 border-black hover:bg-[#FACC15] transition-colors ${n.is_unread ? 'bg-yellow-50' : ''}">
                                ${n.game_image ? `<img src="${n.game_image}" alt="${n.game_name}" class="w-14 h-7 object-cover border-2 border-black shrink-0">` : ''}
                                <div class="flex-1 min-w-0">
                                    <p class="font-black text-[10px] uppercase text-[#0F3A52] truncate">${n.game_name}</p>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-gray-400 line-through text-[9px]">${n.old_price}</span>
                                        <span class="font-black text-[11px] text-[#16A34A]">${n.new_price}</span>
                                        ${n.discount_percent > 0 ? `<span class="bg-[#FACC15] border border-black text-[8px] font-black px-1">-${n.discount_percent}%</span>` : ''}
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-0.5">${n.created_at}</p>
                                </div>
                                ${n.is_unread ? '<span class="w-2 h-2 bg-red-500 border border-black shrink-0 mt-1"></span>' : ''}
                            </a>
                        `).join('');
                    }
                    if (window.lucide) lucide.createIcons();
                })
                .catch(() => {
                    navNotifItems.innerHTML = `<div class="p-4 text-center"><p class="text-xs font-bold text-red-500">Error al cargar.</p></div>`;
                });
        });
    }

    // 6. Global Loading Screens — optimizado
    const globalLoader = document.getElementById('global-loader');
    const globalLoaderGif = document.getElementById('loader-gif');
    const cursorLoader = document.getElementById('cursor-loader');
    const totalGifs = 7;

    // Contenedor oculto con opacity 0.01 para que el browser mantenga los GIFs decodificados
    const hiddenGifContainer = document.createElement('div');
    hiddenGifContainer.style.cssText = `
        position: fixed;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0.01;
        pointer-events: none;
        top: -9999px;
        left: -9999px;
    `;
    document.body.appendChild(hiddenGifContainer);

    const gifElements = [];
    for (let i = 0; i < totalGifs; i++) {
        const img = new Image();
        img.src = `/gifs/${i}.gif`;
        img.style.width = '1px';
        img.style.height = '1px';
        if (img.decode) {
            img.decode().catch(() => {});
        }
        hiddenGifContainer.appendChild(img);
        gifElements.push(img);
    }

    function getRandomGifSrc() {
        const idx = Math.floor(Math.random() * totalGifs);
        return gifElements[idx].src;
    }

    let mouseX = 0, mouseY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        if (cursorLoader && !cursorLoader.classList.contains('hidden')) {
            cursorLoader.style.left = (mouseX + 15) + 'px';
            cursorLoader.style.top  = (mouseY + 15) + 'px';
        }
    });

    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (
            !href ||
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            href.startsWith('data:') ||
            href.startsWith('vbscript:') ||
            link.getAttribute('target') === '_blank' ||
            link.hasAttribute('download')
        ) return;

        if (e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) return;

        e.preventDefault();

        if (cursorLoader) {
            const newSrc = getRandomGifSrc();
            if (cursorLoader.src !== newSrc) cursorLoader.src = newSrc;

            cursorLoader.style.left = (mouseX + 15) + 'px';
            cursorLoader.style.top  = (mouseY + 15) + 'px';
            cursorLoader.classList.remove('hidden', 'opacity-0');
            cursorLoader.classList.add('opacity-100');
        }

        requestAnimationFrame(() => {
            setTimeout(() => { window.location.href = link.href; }, 80);
        });
    });

    document.addEventListener('submit', (e) => {
        const form = e.target.closest('form');
        if (!form || form.getAttribute('target') === '_blank') return;

        if (globalLoader && globalLoaderGif) {
            const newSrc = getRandomGifSrc();
            if (globalLoaderGif.src !== newSrc) globalLoaderGif.src = newSrc;

            globalLoader.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            globalLoader.classList.add('opacity-100');
        }
    });

    window.addEventListener('pageshow', (e) => {
        if (!e.persisted) return;

        if (globalLoader) {
            globalLoader.classList.add('hidden', 'opacity-0', 'pointer-events-none');
            globalLoader.classList.remove('opacity-100');
        }
        if (cursorLoader) {
            cursorLoader.classList.add('hidden', 'opacity-0');
            cursorLoader.classList.remove('opacity-100');
        }
    });
});