document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("imageModal");
  const modalImage = document.getElementById("modalImage");
  const closeModal = document.querySelector(".close");

  // عند النقر على الصورة داخل الرسائل
  document
    .getElementById("chat-messages")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("message-image")) {
        modal.style.display = "block";
        modalImage.src = event.target.src;
      }
    });

  // إغلاق نافذة العرض عند النقر على الزر "X"
  closeModal.onclick = function () {
    modal.style.display = "none";
  };

  // إغلاق النافذة عند النقر خارج الصورة
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
});
// معاينة صورة التقرير قبل  الرفع
document.getElementById("report").addEventListener("change", function (event) {
  const previewContainer = document.getElementById("previewContainer");
  const previewImage = document.getElementById("previewImage");

  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImage.src = e.target.result;
      previewImage.style.display = "block";
    };
    reader.readAsDataURL(file);
  } else {
    previewImage.src = "";
    previewImage.style.display = "none";
  }
});

// change image size in view_report page

function openModal(imageSrc) {
  var modal = document.getElementById("imageModal");
  var modalImage = document.getElementById("modalImage");
  modal.style.display = "block";
  modalImage.src = imageSrc;
}

function closeModal() {
  var modal = document.getElementById("imageModal");
  modal.style.display = "none";
}

// إغلاق النافذة عند النقر خارج الصورة
window.onclick = function(event) {
  var modal = document.getElementById("imageModal");
  if (event.target === modal) {
      closeModal();
  }
}