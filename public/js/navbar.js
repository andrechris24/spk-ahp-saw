const currentRoute = location.href,
    links = document.querySelectorAll(".sidebar-link, .submenu-link");
let list, sublist;
links.forEach((link) => {
    list = link.closest("li.sidebar-item");
    if (link.href === currentRoute) {
        list.classList.add("active");
        if (link.classList.contains("submenu-link")) {
            sublist = link.closest("li.submenu-item");
            sublist.classList.add("active");
        }
    }
});
