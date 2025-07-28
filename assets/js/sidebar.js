document.addEventListener('DOMContentLoaded', function() {
    // Pathname sekarang
    let currentPath = window.location.pathname;
    if (currentPath.endsWith('/')) currentPath = currentPath.slice(0, -1);

    // Biar gampang, mapping menu: href, folder
    const menuGroups = [
        { selector: 'a[href*="../atribut"]', path: '/atribut' },
        { selector: 'a[href*="../nama_pc"]', path: '/nama_pc' },
        { selector: 'a[href*="../cluster"]', path: '/cluster' },
        { selector: 'a[href*="../nilai_pc"]', path: '/nilai_pc' },
        { selector: 'a[href*="../nilai_cluster"]', path: '/nilai_cluster' },
        { selector: 'a[href*="../iterasi"]', path: '/iterasi' },
        { selector: 'a[href*="../laporan"]', path: '/laporan' },
        { selector: 'a[href*="../profile"]', path: '/profile' },
        { selector: 'a[href*="../change_password"]', path: '/change_password' },
        { selector: 'a[href*="../user_management"]', path: '/user_management' },
        { selector: 'a[href*="../user_management"]', path: '/register' },
        { selector: 'a[href*="../home"]', path: '/home' }
    ];

    // Loop semua group
    menuGroups.forEach(group => {
        // Jika path url sekarang mengandung group path
        if(currentPath.includes(group.path)) {
            // Kasih active di nav-link yg sesuai
            document.querySelectorAll('.main-sidebar ' + group.selector).forEach(function(link){
                link.classList.add('active');
                // Kasih menu-open di parent .has-treeview (jika ada)
                let parentTree = link.closest('.has-treeview');
                if(parentTree) {
                    parentTree.classList.add('menu-open');
                    // Kasih active juga di parent nav-link
                    let parentLink = parentTree.querySelector(':scope > .nav-link');
                    if(parentLink) parentLink.classList.add('active');
                }
            });
        }
    });
});
