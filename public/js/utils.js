function confirmDelete() {
    var result = confirm("Do you want to delete this record?");
    return result;
}

function changeNavbarState() {
    if (document.body.classList.contains('nav-md')) {
        Cookies.set('menuState', 'nav-sm', {expires: 7, path: '/panel'});
    }
    if (document.body.classList.contains('nav-sm')) {
        Cookies.set('menuState', 'nav-md', {expires: 7, path: '/panel'});
    }
}