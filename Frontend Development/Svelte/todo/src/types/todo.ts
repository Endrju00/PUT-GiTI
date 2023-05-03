export interface ITodo {
    _id: string;
    text: string;
    done: boolean;
}

export type FiltersType = 'all' | 'completed' | 'active';