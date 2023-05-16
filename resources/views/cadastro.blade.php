<!DOCTYPE html>
<html>
<head>
	<title>Cadastro</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style>
		body {
			background-color: #f7f7f7;
			font-family: Arial, sans-serif;
		}
	</style>
</head>
<body>
	<div class="container mt-4 w-25">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title mb-4 text-center">Cadastro</h4>
				<form action="/cadastro" method="post">
					@csrf
					<div class="form-group">
						<label for="email">E-mail</label>
						<input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
						<div class="invalid-feedback">
							Por favor, digite um e-mail válido.
						</div>
					</div>
					<div class="form-group">
						<label for="name">Nome</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" required>
						<div class="invalid-feedback">
							Por favor, digite seu nome.
						</div>
					</div>
					<div class="form-group">
						<label for="password">Senha</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" minlength="6" required>
						<small id="senhaHelp" class="form-text text-muted">Mínimo de 6 caracteres.</small>
						<div class="invalid-feedback">
							Por favor, digite uma senha com pelo menos 6 caracteres.
						</div>
					</div>
                    <div class="text-center">
					<button type="submit" class="btn btn-primary text-center">Cadastrar</button>
                    </div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script>
		// Adiciona as regras de validação
		(function() {
			'use strict';

			window.addEventListener('load', function() {
				// Seleciona os campos do formulário
				var forms = document.getElementsByClassName('needs-validation');
				// Loop para adicionar as regras de validação em cada campo
				var validation = Array.prototype.filter.call(forms, function(form) {
					form.addEventListener('submit', function(event) {
						if (form.checkValidity() === false) {
							event.preventDefault();
							event.stopPropagation();
						}
						form.classList.add('was-validated');
					}, false);
				});
			}, false);
		})();
	</script>
</body>
</html>
