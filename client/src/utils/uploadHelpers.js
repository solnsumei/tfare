export const openUploadWidget = (cb) => {
	return cloudinary.createUploadWidget({
		cloudName: 'olecha',
		uploadPreset: 'tfares',
		multiple: false,
		cropping: true,
		resourceType: 'image',
		maxFileSize: 500000,
		sources: ['local'],
	}, (error, result) => cb(error, result));
};
