document.addEventListener("DOMContentLoaded", function () {
  function ajaxified() {
    let ajaxify = new Ajaxify({
      elements: "#background, #content, #mainnavbar",
      requestDelay: 500,
      bodyClasses: true,
    });

    window.addEventListener("pronto.request", leavePage);
    window.addEventListener("pronto.render", renderPage);
  }

  // if (Ajaxify)
  if (typeof Ajaxify === "function") ajaxified();

  slugify = ( text ) => {
    
  };

  //TOC
  (function TOC() {
    let elt = document.querySelector("#TableOfContents");
    if (elt === null) return;
    let toc = "";
    headers = document.querySelectorAll("main h2,main h3,main h4");
    for (let i = 0; i < headers.length; i++) {
      const h = headers[i];
      //h.id = "toc-" + i;

      // Slugify Title for use as an ID.
      h.id =  h.innerText
      .toString()
      .normalize( 'NFD' )                   // split an accented letter in the base letter and the acent
      .replace( /[\u0300-\u036f]/g, '' )   // remove all previously split accents
      .toLowerCase()
      .trim()
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-'); 
      const tag = h.tagName.toLowerCase();

      toc += `<a href="#${h.id}" class="${h.tagName}">${h.innerHTML}</a>\n`;
    }
    elt.innerHTML += toc;
    if (headers.length === 0) document.querySelector(".bd-toc").classList.add("d-none");
  })();

  //Scroll indicator

  let scrolly = document.getElementById("scrolly");
  if (scrolly)
    window.addEventListener("scroll", function (e) {
      const b = document.body; //.querySelector("main");
      const d = document.documentElement;
      let st = d.scrollTop || b.scrollTop;
      let p =
        (((d.scrollTop || b.scrollTop) * 100) /
          (d.scrollHeight - window.innerHeight)) |
        0;

      scrolly.style.width = p + "vw";
    });

  // let elts = document.querySelectorAll("table");
  // if (elts)
  //   elts.forEach((elt) => {
  //     elt.classList.add("table", "table-striped", "table-bordered");
  //   });

  // elts = document.querySelectorAll("thead");
  // if (elts)
  //   elts.forEach((elt) => {
  //     elt.classList.add("table-dark");
  //   });

  const modal = document.getElementById("lightboxModal");

  modal.addEventListener("show.bs.modal", (event) => {
    let t = event.relatedTarget; // what triggered the modal

    modal.querySelector(".lightboxContent").innerHTML = t.innerHTML;
  });

  modal.addEventListener("hide.bs.modal", (event) => {
    modal.querySelector(".lightboxContent").innerHTML = "";
  });
  const galleries = document.querySelectorAll("[data-namaskar-gallery]");
  if (modal && galleries)
    galleries.forEach((gallery) => {
      modal.classList.add("gallery");
      console.log;
      let items = gallery.querySelectorAll("[data-namaskar-gallery-item]");
      Namaskar.items = [...items];
      Namaskar.index = 0;
      // items.forEach((item) => {});
    });

  if (modal) {
    modal.querySelector(".arrow.left").addEventListener("click", (e) => {
      e.preventDefault();
      if (Namaskar.index === 0) Namaskar.index = Namaskar.items.length;
      Namaskar.index--;
      modal.querySelector(".lightboxContent").innerHTML =
        Namaskar.items[Namaskar.index].innerHTML;
    });
    modal.querySelector(".arrow.right").addEventListener("click", (e) => {
      e.preventDefault();
      Namaskar.index++;
      if (Namaskar.index === Namaskar.items.length) Namaskar.index = 0;

      modal.querySelector(".lightboxContent").innerHTML =
        Namaskar.items[Namaskar.index].innerHTML;
    });
  }
});

let Namaskar = { items: [], index: 0 };
