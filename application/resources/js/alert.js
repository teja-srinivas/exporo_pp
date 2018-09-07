import sweetalert from 'sweetalert2';
import './alert.scss';

const swalert = sweetalert.mixin({
  confirmButtonText: 'Ja',
  cancelButtonText: 'Nein',
});

export const confirm = message => new Promise((resolve) => {
  swalert({
    type: 'question',
    text: message,
    showCancelButton: true,
    focusCancel: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: () => !alert.isLoading(),
    preConfirm: () => new Promise(resolve2 => resolve(resolve2)),
  });
});
