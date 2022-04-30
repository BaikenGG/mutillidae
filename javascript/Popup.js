const open = document.getElementById('open');
const contenedor = document.getElementById('contenedor');
const close = document.getElementById('close');

open.addEventListener('click', () => {
  contenedor.classList.add('show');  
});

close.addEventListener('click', () => {
  contenedor.classList.remove('show');
});