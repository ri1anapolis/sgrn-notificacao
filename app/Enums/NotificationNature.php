<?php

namespace App\Enums;

enum NotificationNature: string
{
    case realEstateFiduciaryAlienation = 'Alienação Fiduciária de Bem Imóvel';
    case movableEstateFiduciaryAlienation = 'Alienação Fiduciária de Bem Móvel';
    case incorporationPurchaseAndSaleAgreement = 'Compromisso de Compra e Venda Incorporação';
    case subdivisionPurchaseAndSaleAgreement = 'Compromisso de Compra e Venda Loteamento';
    case areaRectification = 'Retificação de Área';
    case usucaption = 'Usucapião';
    case adjudication = 'Adjudicação';
    case other = 'Diversos';
}
