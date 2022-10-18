function countDown() {
    var timer = document.getElementById("timer"); // Timer ID 
    if (count > 0) {
        count--; 
        timer.innerHTML = "Vous serez redirigé·e dans <strong>" + count + "</strong> secondes."; // Timer Message
        setTimeout("countDown()", 1000);
    } else {
        window.location.href = redirect;
    }
}
countDown();

// createPageTransition();
setInterval(createPageTransition, 3000);
function createPageTransition() {
    const el = document.createElement('div');
    el.className = 'page-transition';
    const overlay = document.createElement('div');
    overlay.className = 'page-transition__overlay';
    el.appendChild(overlay);
    document.body.appendChild(el);
    setTimeout(() => {
        el.className = 'page-transition page-transition--in';
    }, 100);
}
