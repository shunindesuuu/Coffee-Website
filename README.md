# Coffee-Website
## This is for school purposes only. It uses PHP, MySQL, HTML, and CSS

EXPLANATION FOR THE SCRIPT IN _adminproducts.php_:

**openFormPopup()**: This function is triggered when called and opens a form popup by setting the display property of the 'popup-container' element to 'flex'.

**closeFormPopup()**: This function is triggered when called and closes the form popup by setting the display property of the 'popup-container' element to 'none'.

**handleProductOptionChange(prodid, selectElement)**: This function is triggered when the product option select element is changed. It takes the selected product ID and the select element as parameters. It determines the selected option and calls the corresponding handler function based on the selected value ('insert', 'delete', or 'edit').

**handleInsertProduct(prodid, selectElement)**: This function is called when the 'insert' option is selected for a product. It retrieves the category of the selected product from the data attribute of the select element. Then, it makes an AJAX request to insert a new product by sending the product ID, category, and option ('insert') to the 'insert_product.php' file. If the request is successful, the page is reloaded.

**handleDeleteProduct(prodid)**: This function is called when the 'delete' option is selected for a product. It displays a confirmation message and if confirmed, makes an AJAX request to delete the product by sending the product ID to the 'delete_product.php' file. If the request is successful, the page is reloaded. If the confirmation is canceled, the select element's value is reset.

**handleProductEdit(prodid)**: This function is called when the 'edit' option is selected for a product. It shows the edit form popup by setting the display property of the 'popup-container' element to 'block'. It sets the value of the 'prodid' input field in the form to the selected product ID. It also updates the heading of the popup to display the product ID. Then, it makes an AJAX request to retrieve the product details for the specified product ID from the 'get_product_details.php' file. Once the response is received, it populates the form fields with the retrieved product details.

**editCategoryName(categoryId)**: This function is called when the edit button is clicked for a category. It allows the user to edit the category name by toggling between edit and save modes. If already in edit mode, it sends an AJAX request to update the category name to the 'update_category.php' file. If the edit mode is entered, it creates an input element with the current category name and appends it to the category element.

**createNewCategory()**: This function is called when creating a new category. It sends an AJAX request to create a new category to the 'create_category.php' file. If successful, the page is reloaded.

**confirmDeleteCategory(categoryId)**: This function is called to confirm and delete a category. It displays a confirmation message and if confirmed, sends an AJAX request to delete the category and its products by sending the category ID to the 'update_category.php' file. If successful, the page is reloaded.
