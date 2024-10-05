/**
 * Main
 */

'use strict';

let menu, animate;

(function () {
  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: 'vertical',
      closeChildren: false
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
    });
  });

  // Display menu toggle (layout-menu-toggle) on hover with delay
  let delay = function (elem, callback) {
    let timeout = null;
    elem.onmouseenter = function () {
      // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(callback, 300);
      } else {
        timeout = setTimeout(callback, 0);
      }
    };

    elem.onmouseleave = function () {
      // Clear any timers set to timeout
      document.querySelector('.layout-menu-toggle').classList.remove('d-block');
      clearTimeout(timeout);
    };
  };
  if (document.getElementById('layout-menu')) {
    delay(document.getElementById('layout-menu'), function () {
      // not for small screen
      if (!Helpers.isSmallScreen()) {
        document.querySelector('.layout-menu-toggle').classList.add('d-block');
      }
    });
  }

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small

  // Auto update menu collapsed/expanded based on the themeConfig
  window.Helpers.setCollapsed(true, false);
})();

document.addEventListener('DOMContentLoaded', function () {
  const imageInput = document.getElementById('formFile');
  const imagePreview = document.getElementById('imagePreview');

  imageInput.addEventListener('change', function () {
      imagePreview.innerHTML = ''; // Clear previous previews
      const files = Array.from(imageInput.files); // Convert file list to an array

      files.forEach((file, index) => {
          // Check if the file is an image
          if (file.type.match('image.*')) {
              const reader = new FileReader();
              reader.onload = function (event) {
                  const imgContainer = document.createElement('div');
                  imgContainer.style.display = 'inline-block';
                  imgContainer.style.position = 'relative';
                  imgContainer.style.margin = '10px';

                  const img = document.createElement('img');
                  img.src = event.target.result;
                  img.style.maxWidth = '150px';
                  imgContainer.appendChild(img);

                  // Create delete icon
                  const deleteIcon = document.createElement('span');
                  deleteIcon.textContent = '×'; // Cross icon
                  deleteIcon.style.position = 'absolute';
                  deleteIcon.style.top = '0';
                  deleteIcon.style.right = '0';
                  deleteIcon.style.cursor = 'pointer';
                  deleteIcon.style.background = '#ff0000';
                  deleteIcon.style.color = '#fff';
                  deleteIcon.style.padding = '2px 5px';
                  deleteIcon.style.borderRadius = '50%';

                  deleteIcon.addEventListener('click', function () {
                      files.splice(index, 1); // Remove the file from the array
                      updateFileList(files); // Update the file input with the new array
                      imgContainer.remove(); // Remove the image preview
                  });

                  imgContainer.appendChild(deleteIcon);
                  imagePreview.appendChild(imgContainer);
              };
              reader.readAsDataURL(file);
          } else {
              const notImage = document.createElement('p');
              notImage.textContent = `${file.name} is not a valid image file.`;
              imagePreview.appendChild(notImage);
          }
      });
  });

  // Function to update the file input with the new files array
  function updateFileList(files) {
      const dataTransfer = new DataTransfer();
      files.forEach(file => dataTransfer.items.add(file));
      imageInput.files = dataTransfer.files;
  }
});