import sweetalert from 'sweetalert2';
import './alert.scss';

const swalert = sweetalert.mixin({
  confirmButtonText: 'Ja',
  cancelButtonText: 'Nein',
});

const ask = message => new Promise((resolve) => {
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

export const confirm = async (message, callback) => {
  const close = await ask(message);

  try {
    await callback();
  } finally {
    close();
  }
};
