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

    //    FIELD VALIDATION

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

        return valid;
    }

    //    ORDER & UPDATE ORDER FORMS

    document.querySelectorAll(".order-form").forEach(form => {

        const submitBtn = form.querySelector("button");
        const fields = form.querySelectorAll("input, textarea");

        // Validate only the field user interacted with
        fields.forEach(field => {
            field.addEventListener("blur", () => {
                validateField(field);
            });
        });

        // Validate all on submit
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

    //    TRACK ORDER FORM

    document.querySelectorAll(".track-form").forEach(form => {

        const mobile = form.querySelector("input[name='mobile']");
        const submitBtn = form.querySelector("button");

        mobile.addEventListener("blur", () => {
            if (!isValidMobile(mobile.value)) {
                showError(mobile, "Enter a valid 10-digit mobile number");
            } else {
                clearError(mobile);
            }
        });

        form.addEventListener("submit", e => {
            if (!isValidMobile(mobile.value)) {
                showError(mobile, "Enter a valid 10-digit mobile number");
                e.preventDefault();
            }
        });
    });

});
