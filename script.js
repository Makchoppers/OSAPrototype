// script.js
document.addEventListener('DOMContentLoaded', function () {
    console.log('OSA System Loaded');

    // Confirm Logout
    const logoutLinks = document.querySelectorAll('a[href*="logout.php"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    });
});
