<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8"> //ssadawdawdawdawdasd
    <title></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 0 auto;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h1></h1>
        <div id="user-info">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="mr-3">Zalogowany jako: <?= $_SESSION['username'] ?></span>
                <button id="logout-button" class="btn btn-outline-danger">Wyloguj</button>
            <?php else: ?>
                <span>Nie jesteś zalogowany.</span>
            <?php endif; ?>
        </div>
    </div>
    <div id="admin-panel" <?php if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) echo 'style="display: none"'; ?>>
        <div class="my-4">
            <h2>Dodaj nowy produkt</h2>
            <form id="add-product-form">
                <div class="form-group">
                    <label for="product-name">Nazwa produktu:</label>
                    <input type="text" id="product-name" name="product_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Dodaj produkt</button>
            </form>
        </div>
        <div class="my-4">
            <h2>Odpowiedzi innych</h2>
            <form id="select-product-form">
                <div class="form-group">
                    <label for="product-select">Wybierz produkt:</label>
                    <select class="form-control" id="product-select">
                      
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Wyświetl odpowiedzi</button>
            </form>
            <div id="responses-container" style="display: none;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Numer</th>
                            <th>Ocena</th>
                            <th>Uwagi</th>
                            <th>Adres IP</th>
                        </tr>
                    </thead>
                    <tbody id="responses-body">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="my-4">
            <h2>Ustaw widoczność wyników ankiety</h2>
          
            <form id="update-visibility-form">
                <div class="form-group">
                    <label for="product-select-visibility">Wybierz produkt:</label>
                    <select id="product-select-visibility" name="product_id" class="form-control" required>
                       
                    </select>
                </div>
                <div class="form-group">
                    <label for="visibility-select">Widoczność wyników ankiety:</label>
                    <select id="visibility-select" name="is_visible" class="form-control" required>
                        <option value="1">Widoczne</option>
                        <option value="0">Niewidoczne</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Zaktualizuj widoczność</button>
            </form>
        </div>
    </div>
    <div id="login-register-form" <?php if (isset($_SESSION['username'])) echo 'style="display: none"'; ?>>
        <div class="row">
            <div class="col">
                <h2>Logowanie</h2>
                <form id="login-form" class="auth-form needs-validation" novalidate>
                    <div class="form-group">
                        <label for="login-username">Nazwa użytkownika:</label>
                        <input type="text" id="login-username" name="username" class="form-control" required>
                        <div class="invalid-feedback">
                            Proszę podać nazwę użytkownika.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Hasło:</label>
                        <input type="password" id="login-password" name="password" class="form-control" required>
                        <div class="invalid-feedback">
                            Proszę podać hasło.
                        </div>
                    </div>
                    <button type="submit" id="login-submit" class="btn btn-primary">Zaloguj</button>
                </form>
            </div>
            <div class="col">
                <h2>Rejestracja</h2>
                <form id="register-form" class="auth-form needs-validation" novalidate>
                    <div class="form-group">
                        <label for="register-username">Nazwa użytkownika:</label>
                        <input type="text" id="register-username" name="username" class="form-control" required>
                        <div class="invalid-feedback">
                            Proszę podać nazwę użytkownika.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Hasło:</label>
                        <input type="password" id="register-password" name="password" class="form-control" required>
                        <div class="invalid-feedback">
                            Proszę podać hasło.
                        </div>
                    </div>
                    <button type="submit" id="register-submit" class="btn btn-primary">Zarejestruj</button>
                </form>
            </div>
        </div>
    </div>

    <div id="survey-form" <?php if (!isset($_SESSION['username']) || $_SESSION['is_admin']) echo 'style="display: none"'; ?>>
        <h2>Formularz oceny</h2>
        <form id="product-survey" class="needs-validation" method="post" novalidate>
            <div class="form-group">
                <label for="product">Wybierz produkt:</label>
                <select id="product" name="product" class="form-control" required>
                    <option value="" disabled selected>Wybierz produkt</option>
                </select>
                <div class="invalid-feedback">
                    Proszę wybrać produkt.
                </div>
            </div>
            <div class="form-group">
                <label for="question1">Pytanie 1: Jak oceniasz produkt?</label>
                <select id="question1" name="question1" class="form-control" required>
                    <option value="" disabled selected>Wybierz ocenę</option>
                    <option value="5">5 - Świetnie</option>
                    <option value="4">4 - Dobrze</option>
                    <option value="3">3 - Średnio</option>
                    <option value="2">2 - Słabo</option>
                    <option value="1">1 - Bardzo źle</option>
                </select>
                <div class="invalid-feedback">
                    Proszę wybrać ocenę produktu.
                </div>
            </div>
            <div class="form-group">
                <label for="question2">Pytanie 2: Twoje uwagi</label>
                <textarea id="question2" name="question2" class="form-control" required></textarea>
                <div class="invalid-feedback">
                    Proszę wpisać swoje uwagi.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Wyślij</button>
        </form>
        <div class="mt-4">
            <div id="responses-container-visible" style="display: none;">
                <h2>Odpowiedzi innych</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Numer</th>
                            <th>Ocena</th>
                            <th>Uwagi</th>
                        </tr>
                    </thead>
                    <tbody id="responses-body-visible">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        function validateForms() {
            $('.needs-validation').each(function() {
                $(this).on('submit', function(event) {
                    if (this.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    $(this).addClass('was-validated');
                });
            });
        }

        validateForms();

        // fetch('get_products.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             const productSelect = $('#product');
        //             data.products.forEach(product => {
        //                 const option = $('<option></option>').val(product.id).text(product.nazwa);
        //                 productSelect.append(option);
        //             });
        //         } else {
        //             alert('Wystąpił błąd przy pobieraniu produktów.');
        //         }
        //     });

        // fetch('get_products.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             const productSelect = $('#product-select');
        //             data.products.forEach(product => {
        //                 const option = $('<option></option>').val(product.id).text(product.nazwa);
        //                 productSelect.append(option);
        //             });
        //         } else {
        //             alert('Wystąpił błąd przy pobieraniu produktów.');
        //         }
        //     });

        // fetch('get_products.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             const productSelect = $('#product-select-visibility');
        //             data.products.forEach(product => {
        //                 const option = $('<option></option>').val(product.id).text(product.nazwa);
        //                 productSelect.append(option);
        //             });
        //         } else {
        //             alert('Wystąpił błąd przy pobieraniu produktów.');
        //         }
        //     });

        $('#product-survey').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);

            if (form[0].checkValidity() === false) {
                event.stopPropagation();
            } else {
                const formData = new FormData(this);

                fetch('submit_survey.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Dziękujemy za wypełnienie ankiety!');
                            $('#product-survey')[0].reset();
                            form.removeClass('was-validated');
                        } else {
                            alert(data.message || 'Wystąpił błąd. Spróbuj ponownie.');
                        }
                    });
            }
            form.addClass('was-validated');
        });

        $('#login-form').on('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('login.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Zalogowano pomyślnie!');
                        $('#user-info').html("<span>Zalogowany jako: " + data.username + "</span> <button id='logout-button' class='btn btn-outline-danger'>Wyloguj</button>");
                        $('#login-register-form').hide();

                        if (data.is_admin) {
                            $('#admin-panel').show();
                        } else {
                            $('#survey-form').show();
                        }

                        attachLogoutEvent();
                    } else {
                        alert(data.message);
                    }
                }).catch(error => {
                console.error('Błąd logowania:', error);
            });
        });

        $('#register-form').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);
            const formData = new FormData(this);

            fetch('register.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Zarejestrowano pomyślnie! Możesz teraz się zalogować.');
                  $('#register-form')[0].reset();
                } else {
                    alert(data.message);
                }
            }).catch(error => {
                console.error('Błąd rejestracji:', error);
            });
        });

        function attachLogoutEvent() {
            $('#logout-button').on('click', function() {
                fetch('logout.php', {
                    method: 'POST',
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Wylogowano pomyślnie.');
                            window.location.reload();
                        } else {
                            alert('Wystąpił błąd podczas wylogowywania.');
                        }
                    }).catch(error => {
                    console.error('Błąd wylogowywania:', error);
                });
            });
        }

        if ($('#logout-button').length) {
            attachLogoutEvent();
        }

        $('#add-product-form').on('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('add_product.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produkt został pomyślnie dodany!');
                        $('#add-product-form')[0].reset();
                    } else {
                        alert('Wystąpił błąd. Spróbuj ponownie.');
                    }
                }).catch(error => {
                console.error('Błąd dodawania produktu:', error);
            });
        });

        $('#product').on('change', function() {
            const productId = $(this).val();
            const responsesBodyVisible = $('#responses-body-visible');
            responsesBodyVisible.empty();

            fetch('get_visibility_responses.php?product_id=' + productId)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        data.responses.forEach((response, index) => {
                            const row = $('<tr></tr>');
                            row.append($('<td></td>').html('<strong>' + (index + 1) + '</strong>'));
                            row.append($('<td></td>').text(response.question1));
                            row.append($('<td></td>').text(response.question2));
                            responsesBodyVisible.append(row);
                        });
                        $('#responses-container-visible').show();
                    } else {
                        $('#responses-container-visible').hide();
                    }
                }).catch(error => {
                    console.error('Błąd pobierania odpowiedzi:', error);
                });
        });

        $('#select-product-form').on('submit', function(event) {
            event.preventDefault();
            const productId = $('#product-select').val();

            fetch('admin_get_responses.php?product_id=' + productId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responsesBody = $('#responses-body');
                        responsesBody.empty();
                        data.responses.forEach((response, index) => {
                            const row = $('<tr></tr>');
                            row.append($('<td></td>').html('<strong>' + (index + 1) + '</strong>'));
                            row.append($('<td></td>').text(response.question1));
                            row.append($('<td></td>').text(response.question2));
                            row.append($('<td></td>').text(response.ip_address));
                            responsesBody.append(row);
                        });
                        $('#responses-container').show();
                    } else {
                        alert(data.message);
                    }
                }).catch(error => {
                console.error('Błąd pobierania odpowiedzi:', error);
            });
        });

        $('#update-visibility-form').on('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('update_visibility.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Widoczność została zaktualizowana.');
                    } else {
                        alert(data.message);
                    }
                }).catch(error => {
                console.error('Błąd aktualizacji widoczności:', error);
            });
        });
    });
</script>
</body>
</html>
