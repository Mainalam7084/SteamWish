document.addEventListener('DOMContentLoaded', () => {
    const contenedores = document.querySelectorAll(".imagen-grid-animada");

    contenedores.forEach(container => {

        const imageUrl = container.dataset.imagen;
        container.style.backgroundImage = `url('${imageUrl}')`;
        container.style.backgroundSize = '100% 100%';
        const rows = 10;
        const cols = 10;
        const tiles = [];

        const fragment = document.createDocumentFragment();

        for (let i = 0; i < rows * cols; i++) {
            const x = i % cols;
            const y = Math.floor(i / cols);
            const div = document.createElement("div");
            div.classList.add("tile");
            div.dataset.row = y;
            div.dataset.col = x;


            div.style.backgroundImage = `url('${imageUrl}')`;
            div.style.backgroundPosition = `${(x / (cols - 1)) * 100}% ${(y / (rows - 1)) * 100}%`;

            fragment.appendChild(div);
            tiles.push(div);
        }

        container.appendChild(fragment);

        function distance(a, b) {
            return Math.abs(a.row - b.row) + Math.abs(a.col - b.col);
        }

        // Hover individual con propagación en vecinos
        tiles.forEach(tile => {
            tile.addEventListener('mouseenter', (e) => {
                const row = parseInt(tile.dataset.row);
                const col = parseInt(tile.dataset.col);

                tiles.forEach(t => {
                    const dist = distance({ row, col }, { row: parseInt(t.dataset.row), col: parseInt(t.dataset.col) });
                    const lift = Math.max(0, 20 - dist * 5);
                    const rx = (Math.random() * 10 - 5).toFixed(2);
                    const ry = (Math.random() * 10 - 5).toFixed(2);

                    t.style.transform = `translateY(-${lift}px) translateZ(${lift * 2}px) rotateX(${rx}deg) rotateY(${ry}deg)`;
                    t.style.boxShadow = `0 ${lift}px 20px rgba(0,0,0,0.4)`;
                });
            });

            tile.addEventListener('mouseleave', () => {
                tiles.forEach(t => {
                    t.style.transform = 'none';
                    t.style.boxShadow = '0 0px 0px rgba(0,0,0,0)';
                });
            });

            // Parallax que sigue el ratón dentro del tile
            tile.addEventListener('mousemove', (e) => {
                const rect = tile.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const cy = rect.top + rect.height / 2;
                const dx = (e.clientX - cx) / rect.width;
                const dy = (e.clientY - cy) / rect.height;

                const rx = dy * 15; // rotación X
                const ry = dx * 15; // rotación Y

                tile.style.transform += ` rotateX(${rx}deg) rotateY(${ry}deg)`;
            });
        });
    });
});