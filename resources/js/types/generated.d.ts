declare namespace App.Data {
export type AddressData = {
hash: string;
address: string;
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
protocol: string;
nature: App.Enums.NotificationNature;
notified_people: any;
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
export type NotificationNature = 'Alienação Fiduciária de Bem Imóvel' | 'Alienação Fiduciária de Bem Móvel' | 'Compromisso de Compra e Venda Incorporação' | 'Compromisso de Compra e Venda Loteamento' | 'Retificação de Área' | 'Usucapião' | 'Adjudicação' | 'Diversos';
export type NotificationStatus = 'completed' | 'in_progress';
export type UserRole = 'admin' | 'employee';
}
