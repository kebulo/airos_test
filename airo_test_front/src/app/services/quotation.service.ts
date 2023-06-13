import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from './auth.service';


@Injectable({
	providedIn: 'root'
})
export class QuotationService {

	private apiUrl = 'http://127.0.0.1:8000/api/';

	constructor(private authService: AuthService, private http: HttpClient) { }

	getQuotation(ages: string, currency_id: string, start_date: string, end_date: string): Observable<any> {
		const token = this.authService.getToken();

		const headers = new HttpHeaders({
			'Content-Type': 'application/json',
			'Authorization': 'Bearer ' + token
		});

		return this.http.post<any>(
			`${this.apiUrl}quotations/calculate-quotation`,
			{ ages, currency_id, start_date, end_date },
			{ headers }
		);
	}
}
