import React, {useContext, useState, useEffect, useRef} from 'react'
import MyContext from './MyContext'

export const FunctionalComponent = () => {
    const msg = useContext(MyContext);

    const [counter, setCounter] = useState(0);
    const [data, setData] = useState([]);
    const [content, setContent] = useState('');

    const input = useRef(null);

    useEffect(() => {     
        setContent(content + "ala ma kota");
        console.log('change');
    }, [counter, data])


    const clickHandler = () => {
        // setCounter(counter + 1);
        setData(['jeden']);
        input.current.focus();
    }

    return (
        <div>
            <button ref={input} onClick={clickHandler}>{counter}</button>
            {msg}<br />
            {JSON.stringify(data)}
            <hr />
            {content}
        </div>
    )
}
