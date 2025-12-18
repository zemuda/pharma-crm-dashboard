import { type BreadcrumbItem } from '@/tailwind/types';
import { AppContent } from '@/tiptap_editor/components/app-content';
import { AppHeader } from '@/tiptap_editor/components/app-header';
import { AppShell } from '@/tiptap_editor/components/app-shell';
import type { PropsWithChildren } from 'react';

export default function AppHeaderLayout({
    children,
    breadcrumbs,
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    return (
        <AppShell>
            <AppHeader breadcrumbs={breadcrumbs} />
            <AppContent>{children}</AppContent>
        </AppShell>
    );
}
