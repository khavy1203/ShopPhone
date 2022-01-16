function toast({
    title,
    message,
    type,
    time
}) {
    //auto remove


    type = type || 'success';
    time = time || 5000;

    var main = document.getElementById("toast");
    if (main) {
        const autoRemoveID = setTimeout(function() {
            main.removeChild(toast);
        }, time + 1000);
        const toast = document.createElement("div");
        const icons = {
            success: 'fas fa-check-circle',
            info: 'fas fa-info-circle',
            warn: 'fas fa-exclamation-circle',
            error: 'fas fa-times'
        };
        //remove when click
        toast.onclick = function(e) {
            if (e.target.closest('.toast__test__close')) {
                main.removeChild(toast);
                clearTimeout(autoRemoveID);
            }
        }
        const icon = icons[type];
        toast.classList.add("toast__test", `toast--${type}`);
        const delay = (time / 1000).toFixed(2);
        toast.style.animation = `slideinleft 0.3s ease, fadeout 1s linear ${delay}s forwards`;
        toast.innerHTML = `
            <div class="toast__test__icon">
                <i class="${icon}"></i>
            </div>
            <div class="toast__test__content">
                <h3 class="toast__test__tittle">
                    ${title}
                </h3>
                <p class="toast__test__message">
                    ${message}
                </p>
            </div>
    
            <div class="toast__test__close">
                <i class="fas fa-times"></i>
            </div>
            `;
        main.appendChild(toast);
    }
}

function addCategory() {

}

function toast_success(title, message, time) {
    toast({
        title,
        message,
        type: 'success',
        time: 3000
    });
}

function toast_info(title, message, time) {
    toast({
        title,
        message,
        type: 'info',
        time: 5000
    });
}

function toast_warn(title, message, time) {
    toast({
        title,
        message,
        type: 'warn',
        time: 5000
    });
}

function toast_error(title, message, time) {
    toast({
        title,
        message,
        type: 'error',
        time: 5000
    });
}