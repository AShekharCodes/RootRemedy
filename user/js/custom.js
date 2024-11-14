// to get current year
function getYear() {
  var currentDate = new Date();
  var currentYear = currentDate.getFullYear();
  document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();

const navbar = document.querySelector(".custom_nav-container");

window.addEventListener("scroll", () => {
  const scrollPosition = window.scrollY || document.documentElement.scrollTop;

  if (scrollPosition > 200) {
    navbar.classList.add("scrolled-navbar");
  } else {
    navbar.classList.remove("scrolled-navbar");
  }
});
