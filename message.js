// Function to generate chat message HTML
function generateMessageHTML(sender, message, timestamp, userType) {
    var userLabel = sender === "Admin" ? "" : "Customer";
    var messageElement = document.createElement("div");
    messageElement.classList.add("message");
    messageElement.innerHTML = `
    <div><strong>${userLabel} ${sender}</strong></div>
    <div>${message}</div>
    <div>${timestamp}</div>
    <button class="reply-button" data-recipient="${sender}">Reply</button>
  `;
    return messageElement;
}

// Function to handle reply button click
function handleReplyClick(event) {
    var recipient = event.target.dataset.recipient;
    var replyInput = document.getElementById("messageInput");
    replyInput.value = "";
    replyInput.placeholder = "Reply to " + recipient;
    replyInput.dataset.recipient = recipient; // Set the recipient value on the dataset
}

// Attach event listener to reply buttons
chatContainer.addEventListener("click", function (event) {
    if (event.target.classList.contains("reply-button")) {
        handleReplyClick(event);
    }
});

// Event listener for sending a message
sendButton.addEventListener("click", function () {
    var newMessage = messageInput.value;
    var recipient = messageInput.dataset.recipient; // Get the recipient from the messageInput dataset

    if (newMessage.trim() !== "" && recipient) {
        // Send the message to the server
        var formData = new FormData();
        formData.append("message", newMessage);
        formData.append("recipient", recipient);

        fetch("insert_chat_data.php", {
            method: "POST",
            body: formData,
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                // Successfully inserted the message, update chat messages
                getChatMessages();
            })
            .catch(function (error) {
                console.log("Error sending chat message:", error);
            });

        messageInput.value = "";
        messageInput.dataset.recipient = ""; // Clear the recipient after sending the message
    }
});

// Function to render chat messages
function renderChatMessages(messages) {
    chatContainer.innerHTML = "";
    for (var i = 0; i < messages.length; i++) {
        var { sender, message, timestamp } = messages[i];
        var messageHTML = generateMessageHTML(sender, message, timestamp);
        chatContainer.appendChild(messageHTML);
    }
}

// Function to retrieve chat messages from the server
function getChatMessages() {
    fetch("read_chat_data.php")
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            renderChatMessages(data);
        })
        .catch(function (error) {
            console.log("Error fetching chat messages:", error);
        });
}

// Initial rendering of chat messages
getChatMessages();

// Function to open the popup form
function openFormPopup() {
    document.getElementById('popup-container').style.display = 'flex';
}

// Function to close the popup form
function closeFormPopup() {
    document.getElementById('popup-container').style.display = 'none';
}
