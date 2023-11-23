    </main>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const menuItems = document.querySelectorAll('#app-header-nav .menu-has-subitems');
            menuItems.forEach((item) => {
                item.addEventListener('click', (event) => {
                    const subMenu = item.querySelector('.menu-sub-menu');
                    if (subMenu) {
                        subMenu.classList.toggle('hidden');
                    }
                });
            });

            const openSidebar = document.querySelector('#open-sidebar');
            const sidebar = document.querySelector('#sidebar');
            openSidebar.addEventListener('click', (event) => {
                sidebar.classList.toggle('-translate-x-full');
            });
        });

        document.querySelector('.logout').addEventListener('click', function(e) {
            e.preventDefault();

            fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'logout': true
                })
            }).then(function(response) {
                if (response.status === 200) {
                    window.location.href = '/';
                }
            });
        });
    </script>


</body>
</html>
