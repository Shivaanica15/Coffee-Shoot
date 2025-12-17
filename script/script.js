document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    const profileImage = document.getElementById('profileImage');
    const messageBtn = document.getElementById('messageBtn');
    const profileEditWindow = document.getElementById('profileEditWindow');
    const usernameDisplay = document.getElementById('username'); // Fetch the username display element
    const profileEditForm = document.getElementById('profileEditForm');
    const profilePictureInput = document.getElementById('profilePictureInput');
    const usernameInput = document.getElementById('usernameInput'); // Fetch the username input element

    // Open the upload window when the button is clicked
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadWindow = document.getElementById('uploadWindow');
    const uploadForm = document.getElementById('uploadForm');

    uploadBtn.addEventListener('click', function () {
        uploadWindow.style.display = 'block';
    });

    uploadForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // File submission handling
        const files = document.getElementById('fileInput').files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileType = file.type.split('/')[0];
            const fileURL = URL.createObjectURL(file);
            let mediaElement;

            if (fileType === 'image') {
                mediaElement = document.createElement('img');
                mediaElement.src = fileURL;
            } else if (fileType === 'video') {
                mediaElement = document.createElement('video');
                mediaElement.src = fileURL;
                mediaElement.controls = true;
            }

            uploadedContent.appendChild(mediaElement);
        }

        uploadWindow.style.display = 'none';
    });

    // Lightbox functionality
    const uploadedContent = document.getElementById('uploadedContent');

    uploadedContent.addEventListener('click', function (event) {
        const target = event.target;

        if (target.tagName === 'IMG' || target.tagName === 'VIDEO') {
            const lightbox = document.getElementById('lightbox');
            const lightboxContent = document.getElementById('lightboxContent');
            lightboxContent.innerHTML = target.outerHTML;
            lightbox.style.display = 'block';
        }
    });

    const closeBtn = document.getElementById('closeBtn');

    closeBtn.addEventListener('click', function () {
        const lightbox = document.getElementById('lightbox');
        lightbox.style.display = 'none';
    });

    // Logout functionality
    logoutBtn.addEventListener('click', function () {
        location.reload();
    });

    // View Profile button functionality
    messageBtn.addEventListener('click', function () {
        profileEditWindow.style.display = 'block';
    });

    // Handle profile editing form submission
    profileEditForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (profilePictureInput.files.length > 0) {
            const profilePicture = profilePictureInput.files[0];
            const profilePictureURL = URL.createObjectURL(profilePicture);
            profileImage.src = profilePictureURL;
        }

        const newUsername = usernameInput.value;
        usernameDisplay.textContent = newUsername; // Update displayed username

        profileEditWindow.style.display = 'none';
    });

    // Fetch username
    function fetchUsername() {
        const username = "John Doe"; 
        usernameDisplay.textContent = username;
    }

    fetchUsername(); // Call fetchUsername to display the username initially
});
