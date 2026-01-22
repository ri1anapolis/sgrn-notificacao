declare namespace App.Data {
export type AddressData = {
id: number;
address: string;
diligences: any | null;
notifiedPeople: any | null;
};
export type AdjudicationData = {
id: number;
office: number | null;
adjudicated_property_registration: string | null;
adjudicated_property_identification: string | null;
adjudicated_property_registry_office: string | null;
};
export type AdversePossessionData = {
id: number;
office: number | null;
adverse_possession_property_registration: string;
adverse_possession_property_identification: string;
adverse_possession_property_registry_office: string;
};
export type AlienationMovablePropertyData = {
id: number;
creditor: string;
office: number | null;
guarantee_movable_property_description: string | null;
contract_registry_data: string | null;
emoluments_intimation: string | null;
contract_number: string | null;
contract_date: string | null;
total_amount_debt: number | null;
debt_position_date: string | null;
default_period: string | null;
grace_period: boolean;
contractual_clause: string | null;
contract_registry_office: string | null;
};
export type AlienationRealEstateData = {
id: number;
creditor: string;
office: number | null;
guarantee_property_registration: string | null;
guarantee_property_address: string | null;
contract_registration_act: string | null;
emoluments_intimation: string | null;
contract_number: string | null;
contract_date: string | null;
total_amount_debt: number | null;
debt_position_date: string | null;
default_period: string | null;
grace_period: boolean;
contractual_clause: string | null;
real_estate_registry_location: string | null;
};
export type DigitalContactData = {
id: number;
contact_date: string | null;
contact_time: string | null;
whatsapp_result: string | null;
email_result: string | null;
custom_result: string | null;
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
history: any | null;
};
export type DiligenceHistoryData = {
id: number;
created_at: string;
user_id: number | null;
user: App.Data.UserData | null;
old_diligence_result_id: number | null;
oldResult: App.Data.DiligenceResultData | null;
new_diligence_result_id: number | null;
newResult: App.Data.DiligenceResultData | null;
old_observations: string | null;
new_observations: string | null;
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
notifiable: any | null;
notifiable_type: string | null;
notified_people: any | null;
addresses: any | null;
public_notice: App.Data.PublicNoticeData | null;
digital_contacts: any | null;
can_download_certificate: boolean;
has_success_diligence: boolean;
has_public_notice: boolean;
};
export type NotifiedPersonData = {
id: number;
name: string;
document: string;
email: string | null;
phone: string | null;
gender: App.Enums.NotifiedPersonGender;
digital_contact: App.Data.DigitalContactData | null;
};
export type OtherData = {
id: number;
creditor: string;
office: number | null;
guarantee_property_registration: string | null;
guarantee_property_address: string | null;
contract_registration_act: string | null;
emoluments_intimation: string | null;
contract_number: string | null;
contract_date: string | null;
total_amount_debt: number | null;
debt_position_date: string | null;
default_period: string | null;
grace_period: boolean;
contractual_clause: string | null;
real_estate_registry_location: string | null;
};
export type PublicNoticeData = {
id: number;
publication_organ: string;
days_between_email_and_notice: number | null;
publications: any | null;
};
export type PublicNoticePublicationData = {
id: number;
publication_order: number;
edition: string | null;
notice_number: string | null;
publication_date: string | null;
};
export type PurchaseAndSaleIncorporationData = {
id: number;
creditor: string;
office: number | null;
committed_property_registration: string | null;
committed_property_identification: string | null;
contract_registration_act: string | null;
emoluments_intimation: string | null;
contract_number: string | null;
contract_date: string | null;
total_amount_debt: number | null;
debt_position_date: string | null;
default_period: string | null;
grace_period: boolean;
contractual_clause: string | null;
real_estate_registry_location: string | null;
};
export type PurchaseAndSaleSubdivisionData = {
id: number;
creditor: string;
office: number | null;
committed_property_registration: string | null;
committed_property_identification: string | null;
contract_registration_act: string | null;
emoluments_intimation: string | null;
contract_number: string | null;
contract_date: string | null;
total_amount_debt: number | null;
debt_position_date: string | null;
default_period: string | null;
grace_period: boolean;
contractual_clause: string | null;
real_estate_registry_location: string | null;
};
export type RetificationAreaData = {
id: number;
office: number | null;
rectifying_property_registration: string | null;
rectifying_property_identification: string | null;
rectifying_property_registry_office: string | null;
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
export type NotifiedPersonGender = 'masculine' | 'feminine';
export type UserRole = 'admin' | 'employee';
}
