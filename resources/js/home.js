// Helper functions
function show(id) {
    document.getElementById(id)?.classList.remove('hidden');
}

function hide(id) {
    document.getElementById(id)?.classList.add('hidden');
}

function priceTag(game, accentClass = 'bg-[#FACC15] text-black') {
    const label = game.discount > 0 ?
        `-${game.discount}% · ${game.price}` :
        game.price;
    return `<span class="shrink-0 ${accentClass} border-2 border-black px-2 py-0.5 font-black text-xs">${label}</span>`;
}

function gameUrl(appid) {
    return `/game?appid=${appid}`;
}

// Shared set of saved appids (populated after loading)
let savedAppids = new Set();

function heartBtn(appid, classes = 'absolute top-2 right-2') {
    const saved = savedAppids.has(Number(appid));
    const active = saved ? 'bg-[#FACC15] text-black' : 'bg-white text-[#0F3A52]';
    return `<button
    data-appid="${appid}"
    class="wishlist-btn shrink-0 w-8 h-8 border-2 border-black flex items-center justify-center
           shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]
           transition-all ${active} ${classes}"
    aria-label="Toggle wishlist">
    <i data-lucide="heart" class="w-3.5 h-3.5"></i>
</button>`;
}

// Render: Most Played list items
function renderMostPlayed(games) {
    const container = document.getElementById('most-played-list');
    if (!games.length) {
        show('most-played-empty');
        return;
    }

    container.innerHTML = games.map((g, i) => {
        const rank = i + 1;
        const badgeCls = rank <= 3 ? 'bg-[#FACC15] text-black' : 'bg-[#0F3A52] text-white';
        const thumb = g.image ?
            `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
            `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

        return `
    <a href="${gameUrl(g.appid)}"
       class="relative flex items-center gap-4 bg-white border-4 border-black p-3
              shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[6px_6px_0_0_#5DA9D6]
              hover:-translate-x-1 hover:-translate-y-1 transition-all group">
        <span class="shrink-0 w-8 h-8 flex items-center justify-center border-2 border-black font-black text-sm ${badgeCls}">#${rank}</span>
        ${thumb}
        <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
        ${priceTag(g)}
        ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
    </a>`;
    }).join('');

    hide('most-played-skeleton');
    show('most-played-list');
    if (window.lucide) lucide.createIcons();
}

// Render: Trending list items
function renderTrending(games) {
    const container = document.getElementById('trending-list');
    if (!games.length) {
        show('trending-empty');
        return;
    }

    container.innerHTML = games.map(g => {
        const thumb = g.image ?
            `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
            `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

        const badge = g.discount > 0 ?
            `<span class="shrink-0 bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5">-${g.discount}% · ${g.price}</span>` :
            `<span class="shrink-0 bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-0.5">${g.price}</span>`;

        return `
    <a href="${gameUrl(g.appid)}"
       class="relative flex items-center gap-4 bg-white border-4 border-black p-3
              shadow-[4px_4px_0_0_#16A34A] hover:shadow-[6px_6px_0_0_#5DA9D6]
              hover:-translate-x-1 hover:-translate-y-1 transition-all group">
        ${thumb}
        <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
        ${badge}
        ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
    </a>`;
    }).join('');

    hide('trending-skeleton');
    show('trending-list');
}

// Render: Upcoming games carousel
function renderUpcoming(games) {
    const container = document.getElementById('upcoming-list');
    if (!games.length) {
        show('upcoming-empty');
        return;
    }

    container.innerHTML = games.map(g => {
        const img = g.image ?
            `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">` :
            `<div class="w-full aspect-[460/215] bg-[#0F3A52] border-b-4 border-black flex items-center justify-center">
               <i data-lucide="calendar" class="w-10 h-10 text-[#5DA9D6]"></i>
           </div>`;

        return `
    <a href="${gameUrl(g.appid)}"
       class="relative group block w-72 shrink-0 bg-white border-4 border-black
              shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6]
              hover:-translate-y-2 hover:-translate-x-1 transition-all">
        ${img}
        ${heartBtn(g.appid, 'absolute top-2 right-2')}
        <div class="p-3 flex flex-col gap-1">
            <h3 class="font-black text-sm uppercase text-[#0F3A52] line-clamp-2 group-hover:text-[#5DA9D6] transition-colors leading-tight">${g.name}</h3>
            <div class="flex items-center justify-between mt-1">
                ${priceTag(g)}
                <span class="text-gray-400 text-xs font-bold uppercase">Coming Soon</span>
            </div>
        </div>
    </a>`;
    }).join('');

    hide('upcoming-skeleton');
    show('upcoming-list');
    if (window.lucide) lucide.createIcons();
}

// Render: Deals carousel (reuses trending data, but filters to only discounted games)
function renderDeals(allGames) {
    const deals = allGames
        .filter(g => g.discount > 0)
        .sort((a, b) => b.discount - a.discount);

    const skeleton = document.getElementById('deals-skeleton');
    const list = document.getElementById('deals-list');
    const empty = document.getElementById('deals-empty');

    if (!deals.length) {
        skeleton?.classList.add('hidden');
        empty?.classList.remove('hidden');
        return;
    }

    list.innerHTML = deals.map(g => {
        const img = g.image ?
            `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">` :
            `<div class="w-full aspect-[460/215] bg-[#0F3A52]/60 border-b-4 border-black flex items-center justify-center"><i data-lucide="gamepad-2" class="w-8 h-8 text-blue-300"></i></div>`;

        const discountBadge = g.discount > 0 ?
            `<span class="inline-block bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5 shadow-[2px_2px_0_0_#000]">-${g.discount}%</span>` :
            '';

        return `
        <a href="${gameUrl(g.appid)}"
           class="relative group block shrink-0 w-52 bg-[#F5F5F5] border-4 border-black
                  shadow-[4px_4px_0_0_#FACC15] hover:shadow-[6px_6px_0_0_#FACC15]
                  hover:-translate-y-1 hover:-translate-x-1 transition-all duration-200 flex flex-col">
            ${img}
            ${heartBtn(g.appid, 'absolute top-2 right-2')}
            <div class="p-3 flex flex-col flex-grow justify-between bg-white">
                <h3 class="text-xs font-black uppercase text-[#0F3A52] line-clamp-2 group-hover:text-[#5DA9D6] transition-colors leading-tight mb-2">${g.name}</h3>
                <div class="flex items-center justify-between gap-1 mt-auto flex-wrap">
                    <span class="inline-block bg-[#FACC15] border-2 border-black px-2 py-0.5 font-black text-xs text-black shadow-[2px_2px_0_0_#000]">${g.price}</span>
                    ${discountBadge}
                </div>
            </div>
        </a>`;
    }).join('');

    skeleton?.classList.add('hidden');
    list.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
}

// Cache Helper functions (2h cache for home data, wishlist is always live)
const CACHE_KEY = 'sw_home_data';
const CACHE_TTL = 2 * 60 * 60 * 1000; // 2 hours in ms

function getCached() {
    try {
        const raw = localStorage.getItem(CACHE_KEY);
        if (!raw) return null;
        const { ts, data } = JSON.parse(raw);
        if (Date.now() - ts > CACHE_TTL) { localStorage.removeItem(CACHE_KEY); return null; }
        return data;
    } catch { return null; }
}

function setCache(data) {
    try { localStorage.setItem(CACHE_KEY, JSON.stringify({ ts: Date.now(), data })); } catch {}
}

// Main render function: takes all data and renders each section
function renderAll(data, ids) {
    savedAppids = new Set(ids.map(Number));
    renderMostPlayed(data.mostPlayed ?? []);
    renderTrending(data.trending ?? []);
    renderUpcoming(data.upcoming ?? []);
    renderDeals(data.trending ?? []);
}

// Main data loading function: tries cache first, then falls back to live fetch
async function loadHomeData() {
    if (!window.HomeConfig) return;

    const cached = getCached();

    // --- FAST PATH: render from cache immediately ---
    if (cached) {
        // Skip loading screen — data is ready now
        const overlay = document.getElementById('sw-loader-overlay');
        if (overlay) overlay.remove();

        // Wishlist IDs are user-specific, always fetch live
        const ids = await fetch(window.HomeConfig.routes.wishlistIds)
            .then(r => r.ok ? r.json() : [])
            .catch(() => []);

        renderAll(cached, ids);
        return;
    }

    // --- SLOW PATH: fresh fetch ---
    try {
        const homePromise = fetch(window.HomeConfig.routes.homeData).then(r => r.json());
        const idsPromise  = fetch(window.HomeConfig.routes.wishlistIds)
            .then(r => r.ok ? r.json() : [])
            .catch(() => []);

        const [data, ids] = await Promise.all([homePromise, idsPromise]);

        setCache(data); // save for next visit
        renderAll(data, ids);
    } catch (err) {
        console.error('Home data load failed:', err);
        ['most-played', 'trending', 'upcoming'].forEach(key => {
            hide(`${key}-skeleton`);
            show(`${key}-empty`);
        });
        // also hide deals skeleton
        document.getElementById('deals-skeleton')?.classList.add('hidden');
        document.getElementById('deals-empty')?.classList.remove('hidden');
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', loadHomeData);
