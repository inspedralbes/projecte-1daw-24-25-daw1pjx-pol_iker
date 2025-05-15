Swal.fire({
    icon: 'error',
    title: 'Hi ha errors al formulari',
    html: errors.join('<br>'),
    confirmButtonText: 'D\'acord'
  });
  
  const tarjetas = document.querySelectorAll('.tarjeta');

  tarjetas.forEach(tarjeta => {
    tarjeta.addEventListener('mouseenter', () => {
      tarjeta.classList.add('activa');
    });
  
    tarjeta.addEventListener('mouseleave', () => {
      tarjeta.classList.remove('activa');
    });
  });
  