import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { DatePipe } from '@angular/common';
import { QuotationService } from '../services/quotation.service';
import { QuotationInterface } from '../interfaces';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
	selector: 'app-quotation',
	templateUrl: './quotation.component.html',
	styleUrls: ['./quotation.component.sass']
})
export class QuotationComponent {
	public errorMessage: string = "";
	public ages: string = "";
	public currency_id: string = "";
	public start_date: Date | undefined;
	public end_date: Date | undefined;
	public quotation: QuotationInterface | undefined;

	constructor(private quotationService: QuotationService, private router: Router, private datePipe: DatePipe) { }

	getQuotation(): void {
		let start_date: any = this.datePipe.transform(this.start_date, 'yyyy-MM-dd');
		let end_date: any = this.datePipe.transform(this.end_date, 'yyyy-MM-dd');


		this.quotationService.getQuotation(this.ages, this.currency_id, start_date, end_date)
			.subscribe({
				next: (v) => this.quotation = v.quotation,
				error: (error: HttpErrorResponse) => {
					if (error.status === 404) {
						this.errorMessage = 'The requested quotation was not found, this may be due to an external error.';
					} else if (error.status === 500) {
						this.errorMessage = 'An internal server error occurred.';
					} else {
						this.errorMessage = 'An error occurred.';
					}

					setTimeout(() => {
						this.errorMessage = "";
					}, 5000)
				},
				complete: () => {
					this.ages = "";
					this.currency_id = "";
					this.start_date = undefined;
					this.end_date = undefined;
				}
			});
	}

	resetQuoation(): void {
		this.quotation = undefined;
	}

}
