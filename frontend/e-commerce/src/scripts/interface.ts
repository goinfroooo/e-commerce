export interface CartItem {
    nom: string;
    prix: number;
    short_desc: string;
    img_path: string;
    article_token: string;
    qte: number;
    standby: boolean;
// Autres propriétés nécessaires
} 

export interface profilItem {
    name: string;
    birthday: string;
    email: string;
    adresse_livraison: string;
    adresse_facturation: string;
    phone: string;
    user_token: string;
    created_at: string;
    // Autres propriétés nécessaires
    }   

export interface articleItem {
    nom: string;
    prix: number;
    short_desc: string;
    description: string;
    img_path: string;
    token: string;
    stock: number;
    // Autres propriétés nécessaires
    }   