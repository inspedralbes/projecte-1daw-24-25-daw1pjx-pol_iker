Swal.fire({
    icon: 'error',
    title: 'Hi ha errors al formulari',
    html: errors.join('<br>'),
    confirmButtonText: 'D\'acord'
  });
  