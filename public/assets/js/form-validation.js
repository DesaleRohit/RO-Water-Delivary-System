document.addEventListener("DOMContentLoaded", () => {

    function showError(input, message) {
        let error = input.nextElementSibling;

        if (!error || !error.classList.contains("field-error")) {
            error = document.createElement("div");
            error.className = "field-error";
            input.insertAdjacentElement("afterend", error);
        }

        error.textContent = message;
        input.classList.add("input-error");
    }

    function clearError(input) {
        const error = input.nextElementSibling;
        if (error && error.classList.contains("field-error")) {
            error.remove();
        }
        input.classList.remove("input-error");
    }

    function isValidMobile(mobile) {
        return /^[0-9]{10}$/.test(mobile);
    }

    function todayDate() {
        const d = new Date();
        return d.toISOString().split("T")[0];
    }

    // FIELD VALIDATION
    function validateField(input) {
        if (!input.name) return true;

        let valid = true;

        if (input.name === "quantity") {
            if (input.value < 1) {
                showError(input, "Quantity must be at least 1");
                valid = false;
            } else {
                clearError(input);
            }
        }

        if (input.name === "mobile") {
            if (!isValidMobile(input.value)) {
                showError(input, "Enter a valid 10-digit mobile number");
                valid = false;
            } else {
                clearError(input);
            }
        }

        if (input.name === "delivery_date") {
            if (input.value < todayDate()) {
                showError(input, "Delivery date cannot be in the past");
                valid = false;
            } else {
                clearError(input);
            }
        }

        if (input.name === "address") {
            if (input.value.trim() === "") {
                showError(input, "Delivery address is required");
                valid = false;
            } else {
                clearError(input);
            }
        }

        if (input.name === "password") {
            if (!/^[0-9]{4}$/.test(input.value)) {
                showError(input, "Password must be exactly 4 digits");
                valid = false;
            } else {
                clearError(input);
            }
        }

        return valid;
    }

    // ORDER & UPDATE ORDER FORMS
    document.querySelectorAll(".order-form").forEach(form => {
        const fields = form.querySelectorAll("input, textarea");

        fields.forEach(field => {
            field.addEventListener("blur", () => {
                validateField(field);
            });
        });

        form.addEventListener("submit", e => {
            let valid = true;
            fields.forEach(field => {
                if (!validateField(field)) {
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
            }
        });
    });

    // Success messages auto-hide
    const messages = document.querySelectorAll(".success-message");
    messages.forEach(msg => {
        setTimeout(() => {
            msg.classList.add("hide");
            setTimeout(() => {
                msg.remove();
            }, 500);
        }, 3000);
    });

    // Admin login error msg
    const errorBox = document.querySelector(".error");
    if (errorBox) {
        setTimeout(() => {
            errorBox.classList.add("fade-out");
        }, 2000);
        setTimeout(() => {
            errorBox.style.display = "none";
        }, 3200);
    }

    // Back to Top button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });

        backToTopBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Contact form success message
    const successMsg = document.getElementById('successMsg');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.transition = 'opacity 0.5s ease';
            successMsg.style.opacity = '0';
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 500);
        }, 3000);

        // Optional: Clear contact form
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.reset();
        }
    }

    // Password change form (if present)
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener("submit", function (e) {
            let newPass = document.querySelector("[name='new_password']").value;
            let confirmPass = document.querySelector("[name='confirm_password']").value;
            let pattern = /^\d{4,8}$/;

            if (!pattern.test(newPass)) {
                alert("Password must be 4 to 8 digits.");
                e.preventDefault();
                return;
            }

            if (newPass !== confirmPass) {
                alert("Passwords do not match.");
                e.preventDefault();
            }
        });
    }

});