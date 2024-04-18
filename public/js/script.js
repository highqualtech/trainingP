
const imageDowncast = (dispatcher) => {
    // Add a listener for the 'insert:image' event
    dispatcher.on('insert:image', (evt, data, conversionApi) => {
        // Get the view element (DOM) representing the image widget
        const imageElement = conversionApi.mapper.toViewElement(data.item);

        // Add your downcast logic here, for example, setting a class
        imageElement.classes.add('your-custom-image-class');
    });
};

ClassicEditor
	.create( document.querySelector( '#editor','' ), {
		// Editor configuration.
        htmlSupport: {
            allow: [ {
                name: 'div',styles:true,classes:true
            }, ],
            disallow: [ /* HTML features to disallow */ ]
        },
        ckfinder: {
            uploadUrl: '/ckfinder/connector?command=QuickUpload&type=Files&responseType=json',
            openerMethod: 'popup',
        },
        mediaEmbed: {
            previewsInData: true
        },
        downcast: {
            // Define the downcast transformations
            image: (modelImage, writer, downcastDispatcher) => {
                // Access the image's attributes
                console.log("image test");
                const { src, alt, width, height } = modelImage.getAttributes();

                // Create a <img> element in the view
                const imageViewElement = writer.createViewElement('img');

                // Set the attributes of the <img> element
                writer.setAttributes({
                    src,
                    alt,
                    width: '300px', // Set your desired width
                    height: '200px', // Set your desired height
                }, imageViewElement);

                // Insert the <img> element into the view
                downcastDispatcher.emit('insert:image', { item: imageViewElement });
            },
        },
	} )
    .then(editor => {
        const currentToolbarConfig = editor.ui.componentFactory.names;

        // Log the current toolbar configuration
        console.log('Current Toolbar Configuration:', currentToolbarConfig);
    })
	.then( editor => {
		window.editor = editor;
	} )
	.catch( handleSampleError );



ClassicEditor
    .create( document.querySelector( '#editor2','' ), {
        // Editor configuration.
        htmlSupport: {
            allow: [ {
                name: 'div',styles:true,classes:true
            }, ],
            disallow: [ /* HTML features to disallow */ ]
        },
        ckfinder: {
            uploadUrl: '/ckfinder/connector?command=QuickUpload&type=Files&responseType=json',
        },
        mediaEmbed: {
            previewsInData: true
        }
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( handleSampleError );

function handleSampleError( error ) {
	const issueUrl = 'https://github.com/ckeditor/ckeditor5/issues';

	const message = [
		'Oops, something went wrong!',
		`Please, report the following error on ${ issueUrl } with the build id "2flfnz9sqb6n-cbtk555k6rl5" and the error stack trace:`
	].join( '\n' );

	console.error( message );
	console.error( error );
}
