export function showAlert(type, title, text = null) {
  Swal.fire({
    icon: type,
    title: title,
    html: text,
    showConfirmButton: false,
    timer: 3500
  });
}

export function loadingAlert(title) {
  Swal.fire({
    title: title,
    showConfirmButton: false,
    allowOutsideClick: false,
    willOpen: () => Swal.showLoading()
  });
}

export function showToast(type, title) {
  const Toast = Swal.mixin({
    toast: true,
    position: "bottom-end",
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });

  Toast.fire({
    icon: type,
    title: title,
  });
}