import Swal from 'sweetalert2';


export const confirmAction = async ({ title, text }) => Swal.fire({
	title,
	text,
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Delete'
});

export const showMessage = ({ text, icon }) =>  {
	if (icon === 'success') {
		return Swal.fire({
			text,
			icon,
			showConfirmButton: false,
			timer: 1500,
		});
	}

	return Swal.fire({
		text,
		icon,
	});
};
