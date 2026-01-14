const today = new Date().toISOString().split('T')[0];

document.getElementById("delivery_date").setAttribute("min", today);