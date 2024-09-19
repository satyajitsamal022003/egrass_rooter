// Banner Slider Js
jQuery(".eg-banner-bx").owlCarousel({
  lazyLoad: true,
  loop: true,
  margin: 20,
  responsiveClass: true,
  animateOut: "fadeOut",
  animateIn: "fadeIn",
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  autoHeight: true,
  mouseDrag: true,
  touchDrag: true,
  smartSpeed: 1000,
  nav: false,
  navText: [
    "<i class='fa fa-chevron-left'></i>",
    "<i class='fa fa-chevron-right'></i>",
  ],
  dots: true,
  responsive: {
    0: {
      items: 1,
    },

    600: {
      items: 1,
    },

    1024: {
      items: 1,
    },

    1366: {
      items: 1,
    },
  },
});


// Banner Slider Js
jQuery(".test-slider").owlCarousel({
  lazyLoad: true,
  loop: true,
  margin: 20,
  responsiveClass: true,
  animateOut: "fadeOut",
  animateIn: "fadeIn",
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  autoHeight: true,
  mouseDrag: true,
  touchDrag: true,
  smartSpeed: 1000,
  nav: false,
  navText: [
    "<i class='fa fa-chevron-left'></i>",
    "<i class='fa fa-chevron-right'></i>",
  ],
  dots: false,
  responsive: {
    0: {
      items: 1,
    },

    600: {
      items: 1,
    },

    1024: {
      items: 1,
    },

    1366: {
      items: 1,
    },
  },
});





// Go To Top
jQuery(document).ready(function () {
  var offset = 220;
  var duration = 500;

  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > offset) {
      jQuery(".back-to-top").fadeIn(duration);
    } else {
      jQuery(".back-to-top").fadeOut(duration);
    }
  });

  jQuery(".back-to-top").click(function (event) {
    event.preventDefault();
    jQuery("html, body").animate({ scrollTop: 0 }, duration);
    return false;
  });
});

// Mobile Menu
$(document).ready(function() {
  $("#cssmenu").menumaker({
    title: "",
    format: "multitoggle",
  });

  $(window).on('scroll', function() {
    if ($(window).scrollTop() > 100) {
      $('.header').addClass('sticky_header animated fadeInDown');
    } else {
      $('.header').removeClass('sticky_header animated fadeInDown');
    }
  });
});

// Bootstrap Tooltip Initialization
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Amount Input Display Logic
document.addEventListener("DOMContentLoaded", function() {
  const amountInputs = document.querySelectorAll('input[name="amount"]');
  const otherAmountContainer = document.getElementById('otherAmountContainer');

  amountInputs.forEach(input => {
    input.addEventListener('change', function() {
      if (this.id === 'am6') {
        otherAmountContainer.style.display = 'block';
      } else {
        otherAmountContainer.style.display = 'none';
      }
    });
  });
});

// Select2 Initialization
$(document).ready(function() {
  $(".js-select2").select2({
    closeOnSelect: false,
    placeholder: "Select political Zone",
    allowClear: true,
    tags: true
  });
});




// Show Input Box Logic
function showInputBox(inputId) {
  // Hide all input boxes
  document.querySelectorAll('.input-box').forEach(function(box) {
    box.style.display = 'none';
  });

  // Show the selected input box
  document.getElementById(inputId).style.display = 'block';
}

// Custom Dropdown Logic
document.addEventListener("DOMContentLoaded", function() {
  const form = document.querySelector(".form");
  const dropdowns = document.querySelectorAll(".dropdown");

  // Check if Dropdowns Exist
  if (dropdowns.length > 0) {
    dropdowns.forEach((dropdown) => {
      createCustomDropdown(dropdown);
    });
  }

  // Check if Form Element Exists on Page
  if (form !== null) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
    });
  }
});

function createCustomDropdown(dropdown) {
  const options = Array.from(dropdown.querySelectorAll("option"));

  // Create Custom Dropdown Element and Add Class Dropdown
  const customDropdown = document.createElement("div");
  customDropdown.classList.add("dropdown");
  dropdown.insertAdjacentElement("afterend", customDropdown);

  // Create Element for Selected Option
  const selected = document.createElement("div");
  selected.classList.add("dropdown-select");
  selected.textContent = options[0].textContent;
  customDropdown.appendChild(selected);

  // Create Element for Dropdown Menu
  const menu = document.createElement("div");
  menu.classList.add("dropdown-menu");
  customDropdown.appendChild(menu);
  selected.addEventListener("click", toggleDropdown.bind(menu));

  // Create Search Input Element
  const search = document.createElement("input");
  search.placeholder = "Search...";
  search.type = "text";
  search.classList.add("dropdown-menu-search");
  menu.appendChild(search);

  // Create Wrapper Element for Menu Items
  const menuInnerWrapper = document.createElement("div");
  menuInnerWrapper.classList.add("dropdown-menu-inner");
  menu.appendChild(menuInnerWrapper);

  // Loop All Options and Create Custom Option for Each Option
  options.forEach((option) => {
    const item = document.createElement("div");
    item.classList.add("dropdown-menu-item");
    item.dataset.value = option.value;
    item.textContent = option.textContent;
    menuInnerWrapper.appendChild(item);

    item.addEventListener("click", setSelected.bind(item, selected, dropdown, menu));
  });

  // Add Selected Class to First Custom Select Option
  menuInnerWrapper.querySelector("div").classList.add("selected");

  // Add Input Event to Search Input Element to Filter Items
  search.addEventListener("input", filterItems.bind(search, options, menu));

  // Add Click Event to Element to Close Custom Dropdown if Clicked Outside
  document.addEventListener("click", closeIfClickedOutside.bind(customDropdown, menu));
  dropdown.style.display = "none";
}

function toggleDropdown() {
  if (this.style.display === "block") {
    this.style.display = "none";
  } else {
    this.style.display = "block";
    this.querySelector("input").focus();
  }
}

function setSelected(selected, dropdown, menu) {
  const value = this.dataset.value;
  const label = this.textContent;

  selected.textContent = label;
  dropdown.value = value;

  menu.style.display = "none";
  menu.querySelector("input").value = "";
  menu.querySelectorAll("div").forEach((div) => {
    div.style.display = "block";
    div.classList.remove("is-select");
  });

  this.classList.add("is-select");
}

function filterItems(items, menu) {
  const customOptions = menu.querySelectorAll(".dropdown-menu-inner div");
  const value = this.value.toLowerCase();

  items.forEach((item, index) => {
    if (item.value.toLowerCase().includes(value)) {
      customOptions[index].style.display = "block";
    } else {
      customOptions[index].style.display = "none";
    }
  });
}

function closeIfClickedOutside(menu, e) {
  if (!this.contains(e.target) && menu.style.display === "block") {
    menu.style.display = "none";
  }
}










$(document).ready(function(){
  $(".newscarouselbx").owlCarousel({
      loop: true,
      margin: 10,
      responsiveClass: true,
      nav: true,
      autoplay: true, // Enable autoplay
      autoplayTimeout: 3000, // Set the delay between transitions (in milliseconds)
      dots: false,
      navText: [
          '<i class="fas fa-chevron-left"></i>',
          '<i class="fas fa-chevron-right"></i>'
      ],
      responsive: {
          0: {
              items: 1
          },
          600: {
              items: 2
          },
          1000: {
              items: 3
          }
      }
  });
});

$(document).ready(function(){
  $(".newsrelatedbx").owlCarousel({
      loop: true,
      margin: 10,
      responsiveClass: true,
      nav: true,
      autoplay: true, // Enable autoplay
      autoplayTimeout: 3000, // Set the delay between transitions (in milliseconds)
      dots: false,
      navText: [
          '<i class="fas fa-chevron-left"></i>',
          '<i class="fas fa-chevron-right"></i>'
      ],
      responsive: {
          0: {
              items: 1
          },
          600: {
              items: 2
          },
          1000: {
              items: 2
          }
      }
  });
});




$(document).ready(function(){
    
  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;
  
  $(".next").click(function(){
  
  current_fs = $(this).parent();
  next_fs = $(this).parent().next();
  previous_fs = $(this).parent().prev();
  
  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
  $("#progressbar li").eq($("fieldset").index(current_fs)).addClass("selected");
  
  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  next_fs.css({'opacity': opacity});
  },
  duration: 600
  });
  });
  
  $(".previous").click(function(){
  
  current_fs = $(this).parent();
  previous_fs = $(this).parent().prev();
  
  //Remove class active
  $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
  $("#progressbar li").eq($("fieldset").index(previous_fs)).removeClass("selected");

  
  //show the previous fieldset
  previous_fs.show();
  
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  previous_fs.css({'opacity': opacity});
  },
  duration: 600
  });
  });
  



  
  $(".submit").click(function(){
  return false;
  })
  
  });



  $(document).ready(function() {
    $("#admin_tog").click(function() {
      $(this).toggleClass("on");
      $("#menu").slideToggle();
    });
  });



  document.getElementById('toggleCheckbox').addEventListener('change', function() {
    var toggleDiv = document.getElementById('toggleDiv');
    if (this.checked) {
        toggleDiv.style.display = 'none';
    } else {
        toggleDiv.style.display = 'block';
    }
});




