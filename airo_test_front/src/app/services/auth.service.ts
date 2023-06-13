import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { JwtHelperService } from '@auth0/angular-jwt';
import { tap } from 'rxjs/operators';


@Injectable({
	providedIn: 'root'
})
export class AuthService {

	private apiUrl = 'http://127.0.0.1:8000/api/';

	constructor(
		private http: HttpClient,
		private jwtHelper: JwtHelperService
	) { }

	login(email: string, password: string): Observable<any> {
		return this.http.post<any>(`${this.apiUrl}login`, { email, password })
			.pipe(
				tap((response: any) => {
					const token = response.authorization.token;
					localStorage.setItem('access_token', token);
				})
			);
	}

	register(name: string, email: string, password: string): Observable<any> {
		return this.http.post<any>(`${this.apiUrl}register`, { name, email, password })
			.pipe(
				tap((response: any) => {
					const token = response.authorization.token;
					localStorage.setItem('access_token', token);
				})
			);
	}

	getToken(): string | null {
		return localStorage.getItem('access_token');
	}

	isAuthenticated(): boolean {
		const token = localStorage.getItem('access_token');
		return !this.jwtHelper.isTokenExpired(token);
	}
}
