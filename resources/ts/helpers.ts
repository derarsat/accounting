import TimeAgo from 'javascript-time-ago'
import en from 'javascript-time-ago/locale/en'
import {Response} from "./services/http";


export class Helpers {
    generateErrors(errors: Array<string>) {
        return errors.join('\r\n')
    }

    generateResponseErrors(response: Response): string {
        const errorsArr = response.errors as unknown as Record<string, Array<string>>
        let error = ""
        Object.keys(errorsArr).map((key) => {
            errorsArr[key].map(errorMessage => error += `${errorMessage} \n`)
        })
        return error
    }

    getTimeAgo(date: Date) {
        TimeAgo.addLocale(en)
        const timeAgo = new TimeAgo('en-US')
        return timeAgo.format(date)

    }

    parse(input: string) {
        const nums = input.replace(/,/g, '').trim()
        if (/^\d+(\.(\d+)?)?$/.test(nums)) return Number(nums)
        return nums === '' ? null : Number.NaN
    }

    format(value: number | null) {
        if (value === null) return ''
        return value.toLocaleString('en-US')
    }


    parseDate(date: Date) {
        return date.toLocaleString("en-US",)
    }

    uppercase(value: string, readable = false) {
        let result = value.charAt(0).toUpperCase() + value.slice(1);
         result = readable && result.replace("_", " ")
        return result
    }
}
