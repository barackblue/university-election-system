
// Toggle Navigation Menu
document.querySelector('.hamburger').addEventListener('click', () => {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
});

//Add user the dashboard
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("add-user-modal");
    const addUserBtn = document.getElementById("add-user-btn");
    const closeBtn = document.querySelector(".close-btn");
    const addRowBtn = document.getElementById("add-row-btn");
    const userRows = document.getElementById("user-rows");

    // Open the modal
    addUserBtn.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "block";
    });

    // Close the modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Add a new row
    addRowBtn.addEventListener("click", () => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><input type="text" name="registration_no[]" required></td>
            <td><input type="text" name="course[]" required></td>
            <td><input type="text" name="full_name[]" required></td>
            <td>
                <select name="role[]" required>
                    <option value="student">Student</option>
                    <option value="candidate">Candidate</option>
                    <option value="lecture">Lecture</option>
                    
                </select>
            </td>
            <td><input type="password" name="password[]" required></td>
            <td><button type="button" class="remove-row-btn">Remove</button></td>
        `;
        userRows.appendChild(row);
    });

    // Remove a row
    userRows.addEventListener("click", (e) => {
        if (e.target.classList.contains("remove-row-btn")) {
            e.target.closest("tr").remove();
        }
    });
});


//profile page
 // JavaScript to handle image preview
 function previewImage(event) {
    const input = event.target;
    const file = input.files[0]; // Get the selected file
    const preview = document.getElementById('profile-photo'); // Get the image element

    // Ensure a file is selected and is an image
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result; // Set the preview image src to the uploaded image
        };
        reader.readAsDataURL(file); // Read the file as a data URL
    } else {
        alert('Please select a valid image file.');
    }
}



//recovery dashbord model

        // Modal functionality

    document.addEventListener('DOMContentLoaded', function () {
        // Get the modal and close button
        var modal = document.querySelector('.modal-content');
        var closeBtn = document.querySelector('.close-btn');
        var recoveryForm = document.getElementById('recovery-form');

        // Close the modal when the "X" button is clicked
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close the modal after recovery email is set (this assumes form is submitted and processed)
        recoveryForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission
            // Here you should add logic to handle the form submission, e.g., AJAX request or simple reload
            // After updating, hide the modal
            modal.style.display = 'none';
            alert('Recovery email updated successfully!');
        });

        // Optionally, you can close the modal if the user clicks outside of the modal
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });


   