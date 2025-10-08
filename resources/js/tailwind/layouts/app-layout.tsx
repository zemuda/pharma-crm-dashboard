import '@/tailwind/css/app.css';
import AppLayoutTemplate from '@/tailwind/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/tailwind/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
        {children}
    </AppLayoutTemplate>
);
