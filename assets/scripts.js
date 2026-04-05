document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('ldp-sidebar');
    const menuBtn = document.getElementById('ldp-menu-btn');
    const closeBtn = document.getElementById('ldp-close-btn');
    const overlay = document.getElementById('overlay');

    if (!sidebar || !menuBtn || !closeBtn || !overlay) return;

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);



    // Course View Toggle Logic (Grid/List)
    const courseListContainer = document.getElementById('ldp-course-list-container');
    const viewBtns = document.querySelectorAll('.ldp-view-btn');

    if (courseListContainer && viewBtns.length > 0) {

        // Load preference from local storage (so it remembers their choice)
        const savedView = localStorage.getItem('ldp_course_view') || 'list';
        if (savedView === 'grid') {
            courseListContainer.classList.add('grid-3');
            document.querySelector('[data-view="list"]').classList.remove('active');
            document.querySelector('[data-view="grid"]').classList.add('active');
        }

        // Add click events to buttons
        viewBtns.forEach(btn => {
            btn.addEventListener('click', function () {

                // Update active button color
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Update layout class and save preference
                if (this.dataset.view === 'grid') {
                    courseListContainer.classList.add('grid-3');
                    localStorage.setItem('ldp_course_view', 'grid');
                } else {
                    courseListContainer.classList.remove('grid-3');
                    localStorage.setItem('ldp_course_view', 'list');
                }
            });
        });
    }

});