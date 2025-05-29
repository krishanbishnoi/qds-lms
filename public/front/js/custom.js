//calendar Script
$('#calendar').datepicker({
  inline: true,
  firstDay: 1,
  // showOtherMonths:true,
  dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
});

function uploadFile(inputNumber) {
  var fileInput = document.getElementById('fileInput' + inputNumber);
  var fileNameInput = document.getElementById('fileNameInput' + inputNumber);

  if (fileInput.files.length > 0) {
    var file = fileInput.files[0];
    fileNameInput.value = file.name;
  } else {
    fileNameInput.value = "No file selected";
  }
}


$('.training-carousel').owlCarousel({
  loop: true,
  margin: 0,
  autoplay:true,
  autoplayHoverPause:true,
  autoplayTimeout:3000,
  nav: true,
  stagePadding: 60,
  dots: false,
  responsive: {
    0: {
      items: 1,
      stagePadding: 0,
      nav: true,
    },
    768: {
      items: 2,
      stagePadding: 0,
      nav: true,
    },
    1200: {
      items: 2,
      nav: true,
    }
  }
});

$('.upcomingg-carousel').owlCarousel({
  loop: true,
  margin: 0,
  autoplay:true,
  autoplayHoverPause:true,
  autoplayTimeout:3000,
  nav: true,
  stagePadding: 60,
  dots: false,
  responsive: {
    0: {
      items: 1,
      stagePadding: 0,
      nav: true,
    },
    768: {
      items: 2,
      stagePadding: 0,
      nav: true,
    },
    1200: {
      items: 2,
      nav: true,
    }
  }
});

function selectall(source) {
  checkboxes = document.getElementsByName('traineeList');
  for (var i = 0, n = checkboxes.length; i < n; i++) {
    checkboxes[i].checked = source.checked;
  }
}

$('.optionBtn').click(function () {
  $("#overllayBg").toggle();
  $('#courseContent').toggleClass("show");
});
$('.sidebarToggle').click(function () {
  $("#overllayBg").toggle();
  $(this).toggleClass("open");
  $('.siderbar').toggleClass("show");
});



// search box
$(document).ready(function () {
  var submitIcon = $('.searchbox-icon');
  var inputBox = $('.searchbox-input');
  var searchBox = $('.searchbox');
  var isOpen = false;
  submitIcon.click(function () {
    if (isOpen == false) {
      searchBox.addClass('searchbox-open');
      inputBox.focus();
      isOpen = true;
    } else {
      searchBox.removeClass('searchbox-open');
      inputBox.focusout();
      isOpen = false;
    }
  });
});

