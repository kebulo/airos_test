import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Injectable({
	providedIn: 'root'
})
export class AuthGuard {
	constructor(private authService: AuthService, private router: Router) { }

	canActivate(
		next: ActivatedRouteSnapshot,
		state: RouterStateSnapshot): boolean | UrlTree {
		return this.checkAuthentication();
	}

	private checkAuthentication(): boolean | UrlTree {
		if (this.authService.isAuthenticated()) {
			return true;
		} else {
			return this.router.createUrlTree(['/login']);
		}
	}

}
