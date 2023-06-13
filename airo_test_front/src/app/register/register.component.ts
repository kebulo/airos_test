import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
	selector: 'app-register',
	templateUrl: './register.component.html',
	styleUrls: ['./register.component.sass']
})
export class RegisterComponent {
	public errorMessage: string = "";
	public name: string = "";
	public email: string = "";
	public password: string = "";

	constructor(private authService: AuthService, private router: Router) { }

	register(): void {
		if (this.name && this.email && this.password) {
			this.authService.register(this.name, this.email, this.password)
				.subscribe({
					next: (v) => console.log(v),
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
		}  else {
			this.setErrorMessage('All the fields are mandatory, please fill them before submiting the formulary');
		}
	}

	setErrorMessage(message: string): void {
		this.errorMessage = message;

		setTimeout(() => {
			this.errorMessage = "";
		}, 5000);
	}
}
