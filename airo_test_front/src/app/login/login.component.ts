import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
	selector: 'app-login',
	templateUrl: './login.component.html',
	styleUrls: ['./login.component.sass']
})
export class LoginComponent {
	public errorMessage: string = "";
	public username: string = "";
	public password: string = "";

	constructor(private authService: AuthService, private router: Router) { }

	login(): void {
		if (this.username && this.password) {
			this.authService.login(this.username, this.password)
				.subscribe({
					next: (v) => {},
					error: (error: HttpErrorResponse) => {
						if (error.status === 404) {
							this.setErrorMessage('The solicitud was not found on the server, please try again');
						} else if (error.status === 500) {
							this.setErrorMessage('An internal server error occurred.');
						} else {
							this.setErrorMessage('There was an error on your request, it could be due to lack of information. Please try again');
						}
					},
					complete: () => {
						this.router.navigate(['/quotation']);
					}
				});
		} else {
			this.setErrorMessage('The username and the password are mandatory, please fill them before submiting the formulary');
		}
	}

	setErrorMessage(message: string): void {
		this.errorMessage = message;

		setTimeout(() => {
			this.errorMessage = "";
		}, 5000);
	}
}
