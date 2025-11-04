<?php
session_start();
require_once("../utilisateur/function.php");
require_once __DIR__ . '/../vendor/autoload.php';

// Désactiver l'affichage des erreurs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Vérification de l'ID prévente
if (!isset($_GET['id_prevente'])) {
    die('Aucun identifiant de prévente fourni.');
}
$id_prevente = intval($_GET['id_prevente']);

// Vérification utilisateur connecté
$user = $_SESSION['connectUser'] ?? null;
if (!$user) {
    die('Utilisateur non connecté.');
}

$facture = getFacture($id_prevente);
if (!$facture) {
    die('Facture introuvable.');
}

// Nettoyer le buffer de sortie
ob_clean();

class PDF_Invoice extends FPDF
{
    private $primaryColor = [41, 98, 255];
    private $secondaryColor = [100, 116, 139];
    private $accentColor = [16, 185, 129];
    
    function Header()
    {
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, 210, 45, 'F');
        
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 28);
        $this->SetXY(15, 15);
        $this->Cell(0, 10, utf8_decode('DRINK & CO'), 0, 1);
        
        $this->SetFont('Arial', 'B', 32);
        $this->SetXY(120, 15);
        $this->Cell(75, 10, utf8_decode('FACTURE'), 0, 1, 'R');
        $this->Ln(25);
    }
    
    function Footer()
    {
        $this->SetY(-25);
        $this->SetDrawColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(3);
        
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Cell(0, 5, utf8_decode('Merci pour votre confiance - DRINK & CO'), 0, 1, 'C');
}
    
    function FancyBox($x, $y, $w, $h, $title, $content, $borderColor, $bgColor = null)
    {
        $this->SetXY($x, $y);
        if ($bgColor) {
            $this->SetFillColor($bgColor[0], $bgColor[1], $bgColor[2]);
            $this->Rect($x, $y, $w, $h, 'F');
        }
        $this->SetFillColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $this->Rect($x, $y, 3, $h, 'F');
        $this->SetDrawColor(220, 220, 220);
        $this->Rect($x, $y, $w, $h);
        
        $this->SetXY($x + 5, $y + 3);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Cell(0, 4, utf8_decode(strtoupper($title)), 0, 1);
        
        $this->SetX($x + 5);
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
        $this->MultiCell($w - 10, 5, utf8_decode($content));
    }
    
    function StatusBadge($x, $y, $status)
    {
        $this->SetXY($x, $y);
        $colors = [
            'En attente' => [[251, 191, 36], [120, 53, 15]],
            'Payé' => [[16, 185, 129], [6, 78, 59]],
            'Annulé' => [[239, 68, 68], [127, 29, 29]],
        ];
        $color = $colors[$status] ?? [[156, 163, 175], [75, 85, 99]];
        $this->SetFillColor($color[0][0], $color[0][1], $color[0][2]);
        $this->SetTextColor($color[1][0], $color[1][1], $color[1][2]);
        $this->SetFont('Arial', 'B', 9);
        $width = $this->GetStringWidth(utf8_decode($status)) + 8;
        $this->RoundedRect($x, $y, $width, 7, 1.5, 'F');
        $this->SetXY($x, $y + 1);
        $this->Cell($width, 5, utf8_decode($status), 0, 0, 'C');
    }
    
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
        $myArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k ));
        $xc = $x+$w-$r; $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k ));
        $this->_Arc($xc + $r*$myArc, $yc - $r, $xc + $r, $yc - $r*$myArc, $xc + $r, $yc);
        $xc = $x+$w-$r; $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$myArc, $xc + $r*$myArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r; $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$myArc, $yc + $r, $xc - $r, $yc + $r*$myArc, $xc - $r, $yc);
        $xc = $x+$r; $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', ($x)*$k, ($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$myArc, $xc - $r*$myArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ',
            $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k,
            $x3*$this->k, ($h-$y3)*$this->k));
    }
}

$pdf = new PDF_Invoice();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 30);

// === Informations prévente ===
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(0, 5, utf8_decode('N° Prévente : PREV-' . str_pad($facture['id_prevente'], 6, '0', STR_PAD_LEFT)), 0, 1, 'R');
$pdf->Cell(0, 5, utf8_decode('Date d\'émission : ') . date('d/m/Y'), 0, 1, 'R');
$pdf->Ln(5);

// === Informations Client & Vendeur ===
$yStart = $pdf->GetY();

$clientText = $user['prenom'] . ' ' . $user['nom'] . "\n" . $user['email'] . "\n";
if (!empty($user['telephone'])) $clientText .= $user['telephone'];
$pdf->FancyBox(15, $yStart, 85, 35, 'info client', $clientText, [41, 98, 255], [239, 246, 255]);

$vendeurText = $facture['nom_entreprise'] . "\n" . $facture['vendeur_email'] . "\n";
if (!empty($facture['vendeur_tel'])) $vendeurText .= $facture['vendeur_tel'];
$pdf->FancyBox(110, $yStart, 85, 35, 'info Vendeur', $vendeurText, [16, 185, 129], [236, 253, 245]);

$pdf->SetY($yStart + 45);

// === Détails ===
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 8, utf8_decode('Détails de la prévente'), 0, 1);

$pdf->SetFillColor(249, 250, 251);
$pdf->SetDrawColor(229, 231, 235);
$pdf->SetLineWidth(0.2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(75, 85, 99);

// En-tête
$pdf->Cell(90, 8, utf8_decode('Produit'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('Prix Unitaire'), 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode('Quantité'), 1, 0, 'C', true);
$pdf->Cell(35, 8, utf8_decode('Total'), 1, 1, 'C', true);

// Produit
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(90, 8, utf8_decode($facture['nom_produit']), 1, 0, 'L');
$pdf->Cell(30, 8, number_format($facture['prix_produit'], 2, ',', ' ') . ' ', 1, 0, 'R');
$pdf->Cell(25, 8, '1', 1, 0, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 8, number_format($facture['prix_prevente'] * 0.80, 2, ',', ' ') . ' ', 1, 1, 'R');

// Totaux
$pdf->Ln(8);
$pdf->SetX(125);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(40, 6, utf8_decode('Sous-total HT :'), 0, 0, 'R');
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(30, 6, number_format($facture['prix_prevente'] * 0.80, 2, ',', ' ') . ' ', 0, 1, 'R');

$tva = $facture['prix_prevente'] * 0.20;
$pdf->SetX(125);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(40, 6, 'TVA (20%) :', 0, 0, 'R');
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(30, 6, number_format($tva, 2, ',', ' ') . ' ', 0, 1, 'R');

$pdf->SetX(125);
$pdf->SetDrawColor(229, 231, 235);
$pdf->Cell(70, 0, '', 'T');
$pdf->Ln(3);

$totalTTC = $facture['prix_prevente'];
$pdf->SetX(125);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(40, 8, utf8_decode('TOTAL TTC :'), 0, 0, 'R');
$pdf->SetTextColor(41, 98, 255);
$pdf->Cell(30, 8, number_format($totalTTC, 2, ',', ' ') . ' ', 0, 1, 'R');


$pdf->Output('I', 'facture_' . $facture['id_prevente'] . '.pdf');
exit;
?>