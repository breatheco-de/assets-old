import React from "react";
import IconEs from './es.svg';
import IconEn from './en.svg';
import { Link } from 'react-router-dom';
import './language_switcher.scss';
const icons = {
    es: IconEs,
    en: IconEn
};

export const LanguageSwitcher = ({ current, translations, onClick }) => {
    const CurrentIcon = icons[current];
    return (<div className="language-switcher">
        <ul>
        {   
            Object.entries(translations).filter(lang => lang[0] !== current).map( lang => {
                const LangIcon = icons[lang[0]];
                return (<li key={lang[0]}><span onClick={() => onClick(lang[1], lang[0])}><LangIcon /></span></li>);
            })
        }
        </ul>
        <span><CurrentIcon /></span>
    </div>);
};