declare namespace App.Data {
export type AddressData = {
id: number;
address: string;
diligences: any | null;
};
export type DiligenceData = {
id: number;
visit_number: number;
observations: string | null;
date: string;
diligence_result_id: number | null;
diligence_result: App.Data.DiligenceResultData | null;
user_id: number | null;
user: App.Data.UserData | null;
};
export type DiligenceResultData = {
id: number;
group: string;
code: string;
description: string;
};
export type NotificationData = {
id: number;
protocol: string;
nature: App.Enums.NotificationNature;
notified_people: any | null;
addresses: any | null;
};
export type UserData = {
id: number;
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
