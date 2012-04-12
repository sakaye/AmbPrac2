<div class="container">
	<div class="form_background">
		<div class="form_container">
			<h1 class="underlined">Register Now</h1>
			<p>Enter the following information to create a new account.</p>
			<form method="post" action="register.php" name="registerform" id="registerform">
				<div class="form_field">
					<label for="username">Username (NUID)</label>
					<input type="text" name="username" id="username" autofocus="autofocus" />
				</div>
				<div class="form_field">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" />
				</div>
				<div class="form_field">
					<label for="re_password">Re-enter Password</label>
					<input type="password" name="re_password" id="re_password" /> 
				</div>
				<div class="form_field">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" />
				</div>
				<div class="form_field">
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" />
				</div>
				<div class="form_field">
					<label for="email">Email Address</label>
					<input type="text" name="email" id="email" />
				</div>
				<div>
					<label for="title">Title</label>
					<select name="title" id="title">
						<option value=""></option>
						<option value="RN">RN</option>
						<option value="CRN">CRN</option>
					</select>
				</div>
				<div>
					<label for="area">Area</label>
					<select name="area" id="area">
						<option value=""></option>
						<option value="Pasadena">Pasadena</option>
						<option value="Glendale">Glendale</option>
						<option value="Irvine">Irvine</option>
					</select>
				</div>
				<input type="submit" name="register" id="submit" class="submit" value="Register"/>
				<div class="clear"></div>
			</form>
		</div>
	</div>
</div>
