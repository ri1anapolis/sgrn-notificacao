declare namespace App.Data {
export type AddressData = {
hash: string;
street: string;
complement: string;
city: string;
zipCode: string;
};
export type DiligenceData = {
hash: string;
visitNumber: number;
diligenceResult: App.Enums.DiligenceResult;
observations: string;
date: string;
};
export type NotificationData = {
hash: string;
name: string;
};
export type UserData = {
hash: string;
name: string;
email: string;
role: App.Enums.UserRole;
};
}
declare namespace App.Enums {
export type DiligenceResult = 'not_found';
export type NotificationStatus = 'completed' | 'in_progress';
export type UserRole = 'admin' | 'employee';
}
