export interface KnowledgeBaseArticle {
  id: string;
  title: string;
  slug: string;
  content: string;
  category: string;
  category_label: string;
  category_color: string;
  tags?: string[];
  author?: {
    id: string;
    name: string;
  };
  views: number;
  is_published: boolean;
  published_at?: string;
  created_at: string;
  updated_at: string;
}

export interface SlaPolicy {
  id: string;
  name: string;
  description?: string;
  priority: 'low' | 'medium' | 'high' | 'critical';
  priority_label: string;
  priority_color: string;
  first_response_hours: number;
  resolution_hours: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export type SupportCategory = 'getting-started' | 'billing' | 'technical' | 'troubleshooting' | 'faq';
