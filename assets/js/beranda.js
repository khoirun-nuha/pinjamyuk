document.addEventListener('DOMContentLoaded', function () {
    // 1. Reveal Animation using Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // 2. Typing Effect for Name
    // IMPORTANT: 'userName' variable must be defined in the PHP file before this script is loaded
    const typingEl = document.getElementById('typing-text');
    if (typingEl && typeof userName !== 'undefined') {
        let i = 0;
        function typeWriter() {
            if (i < userName.length) {
                typingEl.innerHTML += userName.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            } else {
                // Remove blink cursor after typing
                const cursor = document.querySelector('.cursor-blink');
                if (cursor) cursor.style.display = 'none';
            }
        }
        // Start typing after a short delay
        setTimeout(typeWriter, 500);
    }

    // 3. Number Counter Animation
    const counters = document.querySelectorAll('.count-up');
    const speed = 200; // The lower the slower

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + (target > 20 ? inc : 1));
                setTimeout(updateCount, 40);
            } else {
                counter.innerText = target;
            }
        };
        // Trigger when meaningful (e.g. after a delay)
        setTimeout(updateCount, 1000);
    });

    // 4. Realtime Clock
    function updateClock() {
        const now = new Date();
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

        const clockTime = document.getElementById('clock-time');
        const clockDate = document.getElementById('clock-date');

        if (clockTime) clockTime.innerText = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');
        if (clockDate) clockDate.innerText = now.toLocaleDateString('id-ID', dateOptions);
    }
    setInterval(updateClock, 1000);
    updateClock(); // Initial call
});

// Add floating animation custom keyframes
const styleSheet = document.createElement("style");
styleSheet.innerText = `
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}
.animate-float {
    animation: float 4s ease-in-out infinite;
}
@keyframes pulse {
    0% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.1); opacity: 0.2; }
    100% { transform: scale(1); opacity: 0.1; }
}
.cursor-blink {
    animation: blink 1s step-end infinite;
}
@keyframes blink {
    50% { opacity: 0; }
}
`;
document.head.appendChild(styleSheet);
