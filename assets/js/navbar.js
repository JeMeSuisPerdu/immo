document.addEventListener("DOMContentLoaded", function () {
  // Gestion du menu principal
  const dropdownBtn = document.getElementById("btn");
  const dropdownMenu = document.getElementById("dropdown");

  const toggleDropdown = function () {
      dropdownMenu.classList.toggle("show");
  };

  if (dropdownBtn && dropdownMenu) {
      // Toggle dropdown on button click
      dropdownBtn.addEventListener("click", function (e) {
          e.stopPropagation();
          toggleDropdown();
      });

      // Close dropdown when clicking outside
      document.addEventListener("click", function () {
          if (dropdownMenu.classList.contains("show")) {
              toggleDropdown();
          }
      });
  }

  // Gestion des catégories
  const categoryLinks = document.querySelectorAll(".category-link");

  if (categoryLinks.length) {
      categoryLinks.forEach(function (link) {
          link.addEventListener("click", function (event) {
              event.preventDefault();

              const parent = this.parentElement;

              // Toggle active class
              if (parent.classList.contains("active")) {
                  parent.classList.remove("active");
              } else {
                  // Close other active dropdowns
                  document.querySelectorAll(".dropdown-category.active").forEach(function (activeItem) {
                      activeItem.classList.remove("active");
                  });

                  // Open the clicked dropdown
                  parent.classList.add("active");
              }
          });
      });

      // Close category dropdowns when clicking outside
      document.addEventListener("click", function (event) {
          if (!event.target.closest(".dropdown-category")) {
              document.querySelectorAll(".dropdown-category.active").forEach(function (activeItem) {
                  activeItem.classList.remove("active");
              });
          }
      });
  }
});